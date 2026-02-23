<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MagentoOrderController extends Controller
{
    private $magentoBaseUrl;
    private $integrationToken;
    
    public function getOrders(Request $request)
    {
        try {
            // Build query parameters
            $params = [
                'searchCriteria' => []
            ];
            
            // Add pagination if needed
            if ($request->has('page')) {
                $params['searchCriteria']['currentPage'] = $request->page;
            }
            
            if ($request->has('limit')) {
                $params['searchCriteria']['pageSize'] = $request->limit;
            }
            
            // Add filters if needed
            if ($request->has('status')) {
                $params['searchCriteria']['filterGroups'][0]['filters'][0] = [
                    'field' => 'status',
                    'value' => $request->status,
                    'conditionType' => 'eq'
                ];
            }
            
            // Make the API call
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->integrationToken,
                'Content-Type' => 'application/json'
            ])->timeout(30)->get($this->magentoBaseUrl . '/rest/V1/orders', $params);
            
            // Check if response is successful
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }
            
            // Handle specific error cases
            $statusCode = $response->status();
            $errorBody = $response->body();
            
            // Log the error for debugging
            Log::error('Magento API Error', [
                'status' => $statusCode,
                'response' => $errorBody
            ]);
            
            // Parse Magento error message
            $errorMessage = 'Unable to fetch orders';
            if ($statusCode === 401) {
                $errorMessage = 'Authentication failed. Please check your token.';
            } elseif ($statusCode === 403) {
                $errorMessage = 'Authorization failed. Token lacks required permissions.';
                
                // Try to extract the specific resource from the error
                if (strpos($errorBody, 'Magento_Sales::actions_view') !== false) {
                    $errorMessage = 'The integration token needs "Sales" permissions. Please update the integration in Magento Admin.';
                }
            } elseif ($statusCode === 404) {
                $errorMessage = 'API endpoint not found. Check your Magento URL.';
            }
            
            return response()->json([
                'success' => false,
                'error' => $errorMessage,
                'status_code' => $statusCode
            ], $statusCode);
            
        } catch (\Exception $e) {
            Log::error('Magento Order Controller Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while fetching orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get a single order by ID
     */
public function getOrder($orderId)
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->integrationToken,
            'Content-Type' => 'application/json'
        ])->timeout(30)->get($this->magentoBaseUrl . '/rest/V1/orders', [
            'searchCriteria[filter_groups][0][filters][0][field]' => 'entity_id',
            'searchCriteria[filter_groups][0][filters][0][value]' => $orderId,
            'searchCriteria[filter_groups][0][filters][0][condition_type]' => 'eq',
        ]);

        if ($response->successful()) {
            $orders = $response->json();
            $order = $orders['items'][0] ?? null;

            if ($order) {
                return response()->json([
                    'success' => true,
                    'data' => $order
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => false,
            'error' => 'Unable to fetch order',
            'status_code' => $response->status()
        ], $response->status());

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch order',
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function __construct()
    {
        $this->magentoBaseUrl = env('MAGENTO_URL');
        $this->integrationToken = env('MAGENTO_TOKEN');
    }
}