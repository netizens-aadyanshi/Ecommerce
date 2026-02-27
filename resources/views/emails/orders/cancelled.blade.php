<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333;">
    <h2 style="color: #dc2626;">Order Cancelled</h2>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order <strong>#{{ $order->id }}</strong> has been successfully cancelled.</p>

    <p>If this was an error, or if you have questions regarding a refund (if applicable), please contact our support team.</p>

    <p>Any items held for this order have been returned to our stock and are available for others to purchase.</p>

    <p>Thank you for your understanding.</p>
    <a href="{{ route('products.index') }}" style="color: #4f46e5; font-weight: bold;">Continue Shopping</a>
</body>
</html>
