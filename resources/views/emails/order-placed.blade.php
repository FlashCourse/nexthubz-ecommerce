<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $payment->invoice_id }}</title>
</head>

<body style="background-color: #f4f4f4; font-family: Arial, sans-serif; margin: 0; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">INVOICE</h1>
        <div style="margin-bottom: 20px;">
            <p style="font-size: 18px; margin: 0;"><strong>INVOICE NUMBER:</strong> {{ $payment->invoice_id }}</p>
            <p style="font-size: 18px; margin: 0;"><strong>INVOICE DATE:</strong>
                {{ $payment->invoice_date->format('d-m-Y') }}</p>
        </div>
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: bold;">Billed From</h2>
            <p>Next Hubz Limited</p>
            <p>contact@nexthubz.com</p>
            <p>+8809638000380</p>
            <p>House No: 15 (4/B), Road No: 21, Sector-11, Uttara, Dhaka-1230, Bangladesh</p>
        </div>
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: bold;">Billed To</h2>
            <p>{{ $order->user->name }}</p>
            <p>{{ $order->user->email }}</p>
            @if ($order->address)
                <p>{{ $order->address->phone }}</p>
                {{-- <p>{{ $order->address->full_address }}</p> --}}
            @else
                <p>No address found</p>
            @endif
        </div>
        <div style="margin-bottom: 20px;">
            <p style="font-size: 18px; font-weight: bold;">Status: <span
                    style="color: {{ $payment->status == 'completed' ? '#38a169' : '#e53e3e' }};">{{ strtoupper($payment->status) }}</span>
            </p>
        </div>
        <div style="margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Description</th>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Unit Price</th>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Tax</th>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Amount (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItems as $item)
                        <tr>
                            <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->product->name }}</td>
                            <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->quantity }}</td>
                            <td style="border: 1px solid #dddddd; padding: 8px;">BDT
                                {{ number_format($item->price, 2) }}</td>
                            <td style="border: 1px solid #dddddd; padding: 8px;">BDT {{ number_format($item->tax, 2) }}
                            </td>
                            <td style="border: 1px solid #dddddd; padding: 8px;">BDT
                                {{ number_format($item->quantity * $item->price + $item->tax, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-bottom: 20px;">
            <p style="font-size: 18px; font-weight: bold;">Sub Total: BDT {{ number_format($order->subtotal, 2) }}</p>
            <p style="font-size: 18px; font-weight: bold;">Tax: BDT {{ number_format($order->tax, 2) }}</p>
            <p style="font-size: 18px; font-weight: bold;">Shipping: BDT {{ number_format($order->shipping, 2) }}</p>
            <p style="font-size: 18px; font-weight: bold;">Total: BDT {{ number_format($order->total, 2) }}</p>
        </div>
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 20px; font-weight: bold;">Terms and Conditions</h2>
            <p>Please pay within due time.</p>
        </div>
        <div style="text-align: center;">
            <p>Thanks for your payment</p>
        </div>
    </div>
</body>

</html>
