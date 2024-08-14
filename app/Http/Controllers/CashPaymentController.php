<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CashPaymentController extends PaymentController
{
    public function index()
    {

        $sessionData = $this->getSessionData();

        $cartData = $sessionData['cartItems'];
        $orderData = $sessionData['orderData'];

        if (empty($cartData) && empty($orderData)) {
            abort(404);
        }

        return view('cash-payment', compact('cartData', 'orderData'));
    }

    public function pay(Request $request)
    {
        $sessionData = $this->getSessionData();
        $result = $this->processOrder($sessionData['addressData'], $sessionData['orderData'], $sessionData['cartItems']);

        $this->clearSessionData();

        if ($request->user()) {
            $this->sendOrderEmail($request->user(), $result);
        }

        CheckOrderStatus::dispatch($result['order']->id)->delay(now()->addMinutes(30));

        // Generate a unique token and store it in the session
        $token = Str::random(60);
        session()->put('download_token', $token);
        session()->put('order_id', $result['order']->id);
        session()->put('order_success', true);

        // Generate a signed URL that includes the token (set for one-time use)
        $signedUrl = URL::temporarySignedRoute(
            'generate-invoice-pdf',
            now()->addMinutes(10),
            ['order' => $result['order']->id, 'token' => $token]
        );

        return redirect()->route('order-success', ['signedUrl' => $signedUrl]);
    }
}
