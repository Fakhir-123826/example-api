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

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json([
            'error' => 'Unable to fetch orders',
            'details' => $response->body()
        ], $response->status());
    }

    public function getOrdersWithView() {
    $magentoBaseUrl = env('MAGENTO_URL');
    $token = env('MAGENTO_TOKEN');

    $url = $magentoBaseUrl . '/rest/V1/orders?searchCriteria=' . urlencode('{}');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json'
    ])->get($url);

    if ($response->successful()) {
        $data = $response->json(); // decoded array
        $orders = $data['items'] ?? []; // extract the orders
        return view('getData', compact('orders')); // pass to Blade view
    }

    // fallback if request fails
    return view('getData', ['orders' => []]);
}
}