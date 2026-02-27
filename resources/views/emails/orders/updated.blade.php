<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333;">
    <h2 style="color: #4f46e5;">Order Status Updated!</h2>
    <p>Hello {{ $order->user->name }},</p>
    <p>The status of your order <strong>#{{ $order->id }}</strong> has been changed to:</p>

    <div style="padding: 15px; background-color: #e0e7ff; color: #4338ca; display: inline-block; font-weight: bold; border-radius: 8px; font-size: 18px; text-transform: uppercase;">
        {{ $order->status }}
    </div>

    <p style="margin-top: 20px;">You can view your order history and track progress by clicking the button below:</p>
    <a href="{{ route('orders.index') }}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">View Order History</a>
</body>
</html>
