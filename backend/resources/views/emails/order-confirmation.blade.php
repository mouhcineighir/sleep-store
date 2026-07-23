<h1>Thank you for your order, {{ $order->user->name }}!</h1>
<p>Order #{{ $order->id }} has been placed successfully.</p>

<table style="width:100%; border-collapse: collapse;">
    <tr>
        <th style="text-align:left;">Product</th>
        <th>Qty</th>
        <th>Price</th>
    </tr>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>${{ $item->price }}</td>
        </tr>
    @endforeach
</table>

<p><strong>Total: ${{ $order->total }}</strong></p>