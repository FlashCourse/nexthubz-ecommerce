<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use Illuminate\Http\Request;

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

        return redirect()->route('order-success');
    }
}
