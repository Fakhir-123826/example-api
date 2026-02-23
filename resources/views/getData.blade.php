{{-- resources/views/getData.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Magento Orders</h1>

    @if(!empty($orders))
        @foreach($orders as $order)
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-2">Order #{{ $order['increment_id'] }}</h2>
                <p><strong>Status:</strong> {{ ucfirst($order['status']) }}</p>
                <p><strong>Customer:</strong> {{ $order['customer_firstname'] }} {{ $order['customer_lastname'] }} ({{ $order['customer_email'] }})</p>
                <p><strong>Total:</strong> ${{ $order['grand_total'] }}</p>

                <h3 class="mt-4 font-semibold">Items</h3>
                <table class="w-full border border-gray-200 mt-2">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Product</th>
                            <th class="border px-4 py-2">SKU</th>
                            <th class="border px-4 py-2">Qty</th>
                            <th class="border px-4 py-2">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order['items'] as $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item['name'] }}</td>
                                <td class="border px-4 py-2">{{ $item['sku'] }}</td>
                                <td class="border px-4 py-2">{{ $item['qty_ordered'] }}</td>
                                <td class="border px-4 py-2">${{ $item['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <p>No orders found.</p>
    @endif
</div>
@endsection