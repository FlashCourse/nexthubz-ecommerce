<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class SslCommerzPaymentController extends PaymentController
{
    public function index()
    {
        $sessionData = $this->getSessionData();

        $cartData = $sessionData['cartItems'];
        $orderData = $sessionData['orderData'];

        if (empty($cartData) && empty($orderData)) {
            abort(404);
        }

        return view('online-payment', compact('cartData', 'orderData'));
    }


    public function pay(Request $request)
    {
        $user = Auth::user();

        $sessionData = $this->getSessionData();
        $result = $this->processOrder($sessionData['addressData'], $sessionData['orderData'], $sessionData['cartItems']);

        $this->clearSessionData();

        if ($request->user()) {
            $this->sendOrderEmail($request->user(), $result);
        }

        CheckOrderStatus::dispatch($result['order']->id)->delay(now()->addMinutes(30));

        $orderData = $result['order'];
        $addressData = $result['address'];


        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $orderData['total']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        // Create a new payment record
        Payment::create([
            'transaction_id' => $post_data['tran_id'],
            'order_id' => $orderData['id'],
            'amount' => $orderData['total'],
            'payment_method' => 'online',
            'currency' => $post_data['currency'],
        ]);

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name ?? "guest";
        $post_data['cus_email'] = $user->email ?? "guest";
        $post_data['cus_add1'] = " ";
        $post_data['cus_add2'] = " ";
        $post_data['cus_city'] = " ";
        $post_data['cus_state'] = " ";
        $post_data['cus_postcode'] = " ";
        $post_data['cus_country'] = $addressData->country;
        $post_data['cus_phone'] = $addressData->phone;
        $post_data['cus_fax'] = " ";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = $addressData['first_name'] . ' ' . $addressData['last_name'];
        $post_data['ship_add1'] = $addressData['address1'];
        $post_data['ship_add2'] = $addressData['address1'];
        $post_data['ship_city'] = $addressData['city'];
        $post_data['ship_state'] = $addressData['state'];
        $post_data['ship_postcode'] = $addressData['zip_code'];
        $post_data['ship_phone'] = $addressData['phone'];
        $post_data['ship_country'] = $addressData['country'];

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = " ";
        $post_data['product_category'] = " ";
        $post_data['product_profile'] = " ";

        # Additional info
        $post_data['value_a'] = $orderData['id'];

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $order_id = $request->input('value_a');


        $sslc = new SslCommerzNotification();

        // Check payment status in payment table against the transaction id
        $payment = Payment::where('transaction_id', $tran_id)->first();

        if ($payment->status == 'pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successful transaction to customer
                */

                $payment->status = 'completed';
                $payment->payment_method = $request->card_type;
                $payment->save();

                Order::where('id', $payment->order_id)
                    ->update(['status' => 'processing']);

                // Generate a unique token and store it in the session
                $token = Str::random(60);
                session()->put('download_token', $token);
                session()->put('order_id', $order_id);
                session()->put('order_success', true);

                // Generate a signed URL that includes the token (set for one-time use)
                $signedUrl = URL::temporarySignedRoute(
                    'generate-invoice-pdf',
                    now()->addMinutes(10),
                    ['order' => $order_id, 'token' => $token]
                );

                return redirect()->route('order-success', ['signedUrl' => $signedUrl]);
            }
        } else if ($payment->status == 'completed') {
            // That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
            echo "Transaction is successfully Completed";
        } else if ($payment->status == 'failed') {
            // That means through IPN Order status already updated. Now you can just show the customer that transaction is failed. No need to udate database.
            echo "Transaction is failed";
        } else {
            // That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $payment = Payment::where('transaction_id', $tran_id)->first();

        if ($payment->status == 'pending') {

            $payment->status = 'failed';
            $payment->save();

            Order::where('id', $payment->order_id)
                ->update(['status' => 'canceled']);
            return redirect()->route('order-failure')->with('order_failure', true);
        } else if ($payment->status == 'completed') {
            echo "Transaction is already Successful";
        } else if ($payment->status == 'failed' || $payment->status == 'canceled') {
            echo 'Transaction is already failed or canceled';
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $payment = Payment::where('transaction_id', $tran_id)->first();

        if ($payment->status == 'pending') {
            $payment->status = 'canceled';
            $payment->save();

            Order::where('id', $payment->order_id)
                ->update(['status' => 'canceled']);

            $this->restoreStock($payment->order_id);


            return redirect()->route('order-failure')->with('order_failure', true);
        } else if ($payment->status == 'completed') {
            echo "Transaction is already Successful";
        } else if ($payment->status == 'failed' || $payment->status == 'canceled') {
            echo 'Transaction is already failed or canceled';
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            $payment = Payment::where('transaction_id', $tran_id)->first();

            if ($payment->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $payment->amount, $payment->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $payment->status = 'completed';
                    $payment->payment_method = $request->card_type;
                    $payment->save();

                    Order::where('id', $payment->order_id)
                        ->update(['status' => 'processing']);

                    session()->put('order_success', true);
                    return redirect()->route('order-success');
                }
            } else if ($payment->status == 'completed') {
                #That means Order status already updated. No need to udate database.
                echo "Transaction is already successfully Completed";
            } else if ($payment->status == 'failed' || $payment->status == 'canceled') {
                echo "Transaction is failed or canceled";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.
                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
}
