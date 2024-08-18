<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function orderSuccess(Request $request)
    {

        if (session()->has('order_success')) {
            session()->forget('order_success');

            // Retrieve the signed URL from the session or query string
            $signedUrl = $request->query('signedUrl') ?: session('signedUrl');

            // Render the success page with the signed URL for PDF download
            return view('order-success', ['signedUrl' => $signedUrl]);
        } else {
            abort(404);
        }
    }

    public function orderFailure()
    {
        if (session()->has('order_failure')) {
            session()->forget('order_failure');
            return view('order-failure');
        } else {
            abort(404);
        }
    }

    public function generateInvoicePdf(Order $order, Request $request)
    {

        // Verify the signed URL and the session token
        if (! $request->hasValidSignature() || $request->query('token') !== session('download_token')) {
            abort(403, 'Unauthorized action.');
        }

        // Company information
        $companyInfo = [
            'operations_hq' => 'Operations HQ: House No: 15 (4/B), Road No: 21, Sector-11, Uttara, Dhaka-1230, Bangladesh',
            'corporate' => 'Corporate: Plot – 32/C, Tropical Alauddin Tower, Plot-10/E, Road-2, Sector-03, Uttara, Dhaka-1230',
            'phone_1' => '+88 01332-538580',
            'phone_2' => '+88 01332-538581',
            'hotline' => '+88 09638-000380',
            'email' => 'info@example.com',
            'skype_telegram_whatsapp' => 'Skype, Telegram, WhatsApp',
            'get_in_touch' => 'Our team is here 24/7, just share your project details, and we\'ll reach out to you right away.',
        ];

        // Additional notes for the customer
        $additionalNotes = "Thank you for your order! If you have any questions, please contact our support team at " . $companyInfo['hotline'] . " or email us.";

        // Invalidate the session token immediately after use or disable for manually download
        // session()->forget('download_token');

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.order', [
            'order' => $order,
            'companyInfo' => $companyInfo,
            'additionalNotes' => $additionalNotes,
        ]);
        return $pdf->download('order_invoice_' . $order->id . '.pdf');
    }
}
