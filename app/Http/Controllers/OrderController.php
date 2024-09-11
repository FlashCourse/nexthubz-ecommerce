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

        // Invalidate the session token immediately after use or disable for manually download
        // session()->forget('download_token');

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.order', [
            'order' => $order,
        ]);
        return $pdf->download('order_invoice_' . $order->id . '.pdf');
    }
}
