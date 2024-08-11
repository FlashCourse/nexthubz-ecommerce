<!-- resources/views/bkash-refund.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Refund</title>
</head>

<body>
    <h1>bKash Refund Page</h1>
    <form action="{{ route('bkash-refund') }}" method="POST">
        @csrf
        <label for="payment_id">Payment ID:</label>
        <input type="text" id="payment_id" name="payment_id" requigreen><br>

        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" requigreen><br>

        <label for="trx_id">Transaction ID:</label>
        <input type="text" id="trx_id" name="trx_id" requigreen><br>

        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" requigreen><br>

        <label for="reason">Reason:</label>
        <input type="text" id="reason" name="reason" requigreen><br>

        <button type="submit">Submit Refund</button>
    </form>

    @if (session('successMsg'))
        <p>{{ session('successMsg') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif
</body>

</html>
