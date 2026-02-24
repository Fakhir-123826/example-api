<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        📦 Magento Orders
    </h1>

    @if(isset($orders['items']) && count($orders['items']) > 0)

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Order #</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase">Date</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @foreach($orders['items'] as $order)
                        <tr class="hover:bg-gray-50 transition duration-200">

                            <td class="px-6 py-4 font-semibold text-gray-700">
                                {{ $order['increment_id'] }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $order['customer_firstname'] ?? '' }}
                                {{ $order['customer_lastname'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $order['customer_email'] }}
                            </td>

                            <td class="px-6 py-4 text-green-600 font-semibold">
                                ${{ number_format($order['grand_total'], 2) }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($order['status']) {
                                        'processing' => 'bg-yellow-100 text-yellow-700',
                                        'complete' => 'bg-green-100 text-green-700',
                                        'canceled' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    @else
        <div class="bg-white p-8 rounded-2xl shadow text-center">
            <p class="text-gray-500 text-lg">No orders found.</p>
        </div>
    @endif

</div>

</body>
</html>