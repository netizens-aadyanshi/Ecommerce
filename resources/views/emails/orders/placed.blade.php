<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333;">
    <h2 style="color: #4f46e5;">Thank you for your order, {{ $order->user->name }}!</h2>
    <p>We've received your order <strong>#{{ $order->id }}</strong> and are getting it ready.</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Product</th>
                <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Qty</th>
                <th style="padding: 10px; text-align: right; border: 1px solid #ddd;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $item->product->name }}</td>
                <td style="padding: 10px; text-align: center; border: 1px solid #ddd;">{{ $item->quantity }}</td>
                <td style="padding: 10px; text-align: right; border: 1px solid #ddd;">${{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold;">Total Amount:</td>
                <td style="padding: 10px; text-align: right; font-weight: bold; color: #4f46e5;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 20px;"><strong>Shipping Address:</strong><br>{{ $order->shipping_address }}</p>
    <p>We will notify you when your order status changes.</p>
</body>
</html>
