<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\CheckOrderStatus;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;


class BkashController extends PaymentController
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    public function __construct()
    {
        $this->base_url = config('services.bkash.base_url');
        $this->app_key = config('services.bkash.app_key');
        $this->app_secret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
    }

    public function index()
    {
        $sessionData = $this->getSessionData();

        $cartData = $sessionData['cartItems'];
        $orderData = $sessionData['orderData'];

        if (empty($cartData) && empty($orderData)) {
            abort(404);
        }

        return view('bkash-payment', compact('cartData', 'orderData'));
    }

    public function getToken()
    {
        Session::forget('bkash_token');

        $post_token = [
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        ];

        $url = "$this->base_url/checkout/token/grant";
        $header = [
            'Content-Type' => 'application/json',
            'password' => $this->password,
            'username' => $this->username
        ];

        $response = Http::withHeaders($header)->post($url, $post_token);
        $responseData = $response->json();

        if (array_key_exists('msg', $responseData)) {
            return $responseData;
        }

        Session::put('bkash_token', $responseData['id_token']);

        return response()->json(['success' => true]);
    }

    public function createPayment(Request $request)
    {
        $sessionData = $this->getSessionData();
        $result = $this->processOrder($sessionData['addressData'], $sessionData['orderData'], $sessionData['cartItems']);
        $this->clearSessionData();
        $orderData = $result['order'];
        if ($request->user()) {
            $this->sendOrderEmail($request->user(), $result);
        }
        CheckOrderStatus::dispatch($result['order']->id)->delay(now()->addMinutes(30));


        $token = Session::get('bkash_token');

        // Dynamically merge the amount into the request
        $request->merge([
            'amount' => round($orderData['total'], 2),
            'intent' => 'sale',
            'currency' => 'BDT',
            'merchantInvoiceNumber' => rand()
        ]);

        $url = "$this->base_url/checkout/payment/create";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->post($url, $request->all());

        return $response->json();
    }

    public function executePayment(Request $request)
    {
        $token = Session::get('bkash_token');

        $paymentID = $request->paymentID;
        $url = "$this->base_url/checkout/payment/execute/$paymentID";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->post($url);

        return $response->json();
    }

    public function queryPayment(Request $request)
    {
        $token = Session::get('bkash_token');
        $paymentID = $request->payment_info['payment_id'];

        $url = "$this->base_url/checkout/payment/query/$paymentID";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->get($url);

        return $response->json();
    }

    // public function successPayment(Request $request)
    // {

    //     // IF PAYMENT SUCCESS THEN YOU CAN APPLY YOUR CONDITION HERE
    //     if ('Noman' == 'success') {

    //         // THEN YOU CAN REDIRECT TO YOUR ROUTE

    //         Session::flash('successMsg', 'Payment has been Completed Successfully');

    //         return response()->json(['status' => true]);
    //     }

    //     Session::flash('error', 'Noman Error Message');

    //     return response()->json(['status' => false]);
    // }

    public function successPayment(Request $request)
    {
        // Retrieve paymentID from the request
        $paymentID = $request->input('paymentID');

        // Retrieve token from session
        $token = Session::get('bkash_token');

        // bKash payment query URL
        $url = "$this->base_url/checkout/payment/query/$paymentID";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        // Query the payment to verify its status
        $response = Http::withHeaders($header)->get($url);
        $paymentDetails = $response->json();

        // Check if the payment was successful
        if (isset($paymentDetails['transactionStatus']) && $paymentDetails['transactionStatus'] == 'Completed') {
            // Update the payment status and order status in your system
            $orderId = $paymentDetails['merchantInvoiceNumber']; // Assuming merchantInvoiceNumber is your order identifier
            $order = Order::find($orderId);

            if ($order) {
                // Update payment and order statuses
                Payment::where('transaction_id', $paymentID)->update(['status' => 'completed', 'payment_method' => 'bKash']);
                $order->update(['status' => 'processing']);

                // Generate a unique token and store it in the session
                $token = Str::random(60);
                session()->put('download_token', $token);
                session()->put('order_id', $order->id);
                session()->put('order_success', true);

                // Generate a signed URL for downloading the invoice
                $signedUrl = URL::temporarySignedRoute(
                    'generate-invoice-pdf',
                    now()->addMinutes(10),
                    ['order' => $order->id, 'token' => $token]
                );

                // Redirect to the success page with the signed URL
                return redirect()->route('order-success', ['signedUrl' => $signedUrl]);
            } else {
                // Order not found
                Session::flash('error', 'Order not found.');
                return redirect()->route('order-failure')->with('order_failure', true);
            }
        } else {
            // Payment failed or incomplete
            Session::flash('error', 'Payment failed or incomplete.');
            return redirect()->route('order-failure')->with('order_failure', true);
        }
    }
}
