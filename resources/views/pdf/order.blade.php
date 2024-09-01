<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .company-info,
        .customer-info,
        .order-summary {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            vertical-align: top;
            color: #555;
        }

        .info-table td.label {
            font-weight: bold;
            width: 150px;
            color: #333;
        }

        .order-summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-summary-table th,
        .order-summary-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .order-summary-table th {
            background-color: #f8f8f8;
            color: #333;
            font-weight: bold;
        }

        .order-summary-table td {
            background-color: #fff;
            color: #555;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #888;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer strong {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Order Placed</h1>
            <p>Order Date: {{ $order->created_at->format('Y-m-d') }}</p>
            <p>Order Number: {{ $order->order_number }}</p>
        </div>

        <div class="company-info">
            <h2 class="section-title">Company Information</h2>
            <table class="info-table">
                <tr>
                    <td class="label">Operations HQ:</td>
                    <td>{{ $companyInfo['operations_hq'] }}</td>
                </tr>
                <tr>
                    <td class="label">Corporate:</td>
                    <td>{{ $companyInfo['corporate'] }}</td>
                </tr>
                <tr>
                    <td class="label">Phone:</td>
                    <td>{{ $companyInfo['phone_1'] }} / {{ $companyInfo['phone_2'] }}</td>
                </tr>
                <tr>
                    <td class="label">Hotline:</td>
                    <td>{{ $companyInfo['hotline'] }}</td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td>{{ $companyInfo['email'] }}</td>
                </tr>
                <tr>
                    <td class="label">Skype, Telegram, WhatsApp:</td>
                    <td>{{ $companyInfo['skype_telegram_whatsapp'] }}</td>
                </tr>
            </table>
        </div>

        <div class="customer-info">
            <h2 class="section-title">Customer Information</h2>
            <table class="info-table">
                <tr>
                    <td class="label">Name:</td>
                    <td>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</td>
                </tr>
                <tr>
                    <td class="label">Address:</td>
                    <td>
                        {{ $order->shipping_address_line_1 }}<br>
                        @if ($order->shipping_address_line_2)
                            {{ $order->shipping_address_line_2 }}<br>
                        @endif
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postcode }}<br>
                        {{ $order->shipping_country }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Phone:</td>
                    <td>{{ $order->shipping_phone }}</td>
                </tr>
                @if ($order->shipping_email)
                    <tr>
                        <td class="label">Email:</td>
                        <td>{{ $order->shipping_email }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="order-summary">
            <h2 class="section-title">Order Summary</h2>
            <table class="order-summary-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price (BDT)</th>
                        <th>Total (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>BDT {{ number_format($item->price, 2) }}</td>
                            <td>BDT {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="total-amount">Subtotal:</td>
                        <td class="total-amount">BDT {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="total-amount">Taxes (VAT/GST):</td>
                        <td class="total-amount">BDT {{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="total-amount">Shipping:</td>
                        <td class="total-amount">BDT {{ number_format($order->shipping_cost, 2) }}</td>
                    </tr>
                    @if ($order->total_discount)
                        <tr>
                            <td colspan="3" class="total-amount">Discount:</td>
                            <td class="total-amount">- BDT {{ number_format($order->total_discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="total-amount">Total Amount:</td>
                        <td class="total-amount">BDT {{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><strong>{{ $additionalNotes }}</strong></p>
            <p>Our team is here 24/7, just share your project details, and we'll reach out to you right away.</p>
        </div>
    </div>
</body>

</html>
