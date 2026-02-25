<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; }
        .button { background: #4a90e2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hi, {{ $user->name }}!</h2>
        <p>Thank you for joining our **Ecommerce Store**. We're excited to have you!</p>
        <p>Your account has been created with the role: <strong>{{ $user->role }}</strong>.</p>
        <p>Start shopping for the best products at the best prices.</p>
        <br>
        <a href="{{ url('/') }}" class="button">Visit Our Shop</a>
        <br><br>
        <p>Happy Shopping!</p>
    </div>
</body>
</html>
