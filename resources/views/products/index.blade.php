<h2>Product List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>SKU</th>
        <th>Price</th>
    </tr>

    @foreach($products['items'] as $product)
    <tr>
        <td>{{ $product['id'] }}</td>
        <td>{{ $product['name'] }}</td>
        <td>{{ $product['sku'] }}</td>
        <td>
            {{ $product['price'] ?? 'N/A' }}
        </td>
    </tr>
    @endforeach
</table>