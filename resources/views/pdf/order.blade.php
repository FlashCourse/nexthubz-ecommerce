<!DOCTYPE html>
<html>

<head>
    <title>Order Invoice</title>
    <style>
        /* Add your styles here */
    </style>
</head>

<body>
    <h1>Order Invoice</h1>
    <p>Order ID: {{ $order->id }}</p>
    <p>Total Price: &#2547;{{ $order->total_price }}</p>
    <!-- Add more order details here -->
</body>

</html>
