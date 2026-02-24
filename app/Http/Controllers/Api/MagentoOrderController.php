<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MagentoOrderController extends Controller
{
    public function getOrders()
    {
        // Magento API URL

        $magentoBaseUrl = env('MAGENTO_URL');
        $token = env('MAGENTO_TOKEN');

        // Build the URL with proper searchCriteria parameter
        $url = $magentoBaseUrl . '/rest/V1/orders?searchCriteria=' . urlencode('{}');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($url);

        // if ($response->successful()) {
        //     return response()->json($response->json());
        // }
        if ($response->successful()) {
            $orders = $response->json();
            return view('orders.index', compact('orders'));
        }

        return response()->json([
            'error' => 'Unable to fetch orders',
            'details' => $response->body()
        ], $response->status());
    }
    public function getOrdersFormApi()
    {
        try {
            $magentoBaseUrl = env('MAGENTO_URL');
            $token = env('MAGENTO_TOKEN');

            if (!$magentoBaseUrl || !$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Magento configuration missing'
                ], 500);
            }

            $url = $magentoBaseUrl . '/rest/V1/orders?searchCriteria=' . urlencode('{}');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->timeout(30)->get($url);

            // ✅ If successful
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            // ❌ Magento returned error
            return response()->json([
                'success' => false,
                'message' => 'Magento API error',
                'status' => $response->status(),
                'details' => $response->json() ?? $response->body()
            ], $response->status());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request exception',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProducts()
    {
        $magentoBaseUrl = env('MAGENTO_URL');
        $token = env('MAGENTO_TOKEN');

        $url = $magentoBaseUrl . '/rest/V1/products?searchCriteria=' . urlencode('{}');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $products = $response->json();
            // return response()->json($response->json());
            return view('products.index', compact('products'));
        }

        return response()->json([
            'error' => 'Unable to fetch products',
            'details' => $response->body()
        ], $response->status());
    }
    public function getProductsFromApi($page = 1, $pageSize = 10)
    {
        try {
            $magentoBaseUrl = env('MAGENTO_URL');
            $token = env('MAGENTO_TOKEN');

            if (!$magentoBaseUrl || !$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Magento configuration missing'
                ], 500);
            }

            // ✅ Add pagination params
            $url = $magentoBaseUrl . '/rest/V1/products?searchCriteria[currentPage]=' . $page . '&searchCriteria[pageSize]=' . $pageSize;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Magento API error',
                'status' => $response->status(),
                'details' => $response->json() ?? $response->body()
            ], $response->status());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request exception',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
