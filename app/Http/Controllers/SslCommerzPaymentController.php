<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StockService;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SslCommerzPaymentController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    public function index()
    {
        $cartData = session('cartData', []);
        $orderData = session('orderData', []);

        // Check if both cartData and orderData are empty
        if (empty($cartData) && empty($orderData)) {
            abort(404);
        }

        return view('online-payment', compact('cartData', 'orderData'));
    }


    public function pay(Request $request)
    {

        $user = Auth::user();

        $cartItems = [];

        $orderData = [];

        $addressData = [];

        $addressData = session()->get('addressData');

        $orderData = session()->get('orderData');

        $cartItems = session()->get('cartData');

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Retrieve the quantities for all products and variants
            $productQuantities = [];
            foreach ($cartItems as $item) {
                $productQuantities[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity']
                ];
            }

            $this->stockService->deductStock($productQuantities);

            // Use firstOrCreate directly on the Address model
            $address = Address::firstOrCreate(['user_id' => $user->id], $addressData);

            // create order
            $order = Order::create([
                'user_id' => $user->id,
                'address' => $address->id,
                'payment_method' => 'online',
                'tax' => $orderData['tax'],
                'shipping' => $orderData['shipping'],
                'subtotal' => $orderData['subtotal'],
                'total' => $orderData['total'],
                'status' => 'pending',
            ]);

            // Prepare data for order items insertion
            $orderItemsData = [];
            foreach ($cartItems as $item) {
                $orderItem = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ];

                if (!empty($item['variant_id'])) {
                    $orderItem['variant_id'] = $item['variant_id'];
                }

                $orderItemsData[] = $orderItem;
            }

            // Insert all the order items to the CartItem
            OrderItem::insert($orderItemsData);

            // Dispatch the job to rollback stock if checkout failed
            CheckOrderStatus::dispatch($order->id)->delay(now()->addMinutes(5));

            session()->forget('cartData');
            session()->forget('orderData');

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            // Handle the exception, log or throw it if necessary
            throw $e;
        }




        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $orderData['total']; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        // Create a new payment record
        Payment::create([
            'invoice_id' => uniqid(),
            'transaction_id' => $post_data['tran_id'],
            'order_id' => $order->id,
            'amount' => $orderData['total'],
            'payment_method' => 'online',
            'currency' => $post_data['currency'],
            'payment_date' => now(),
        ]);

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name;
        $post_data['cus_email'] = $user->email;
        $post_data['cus_add1'] = " ";
        $post_data['cus_add2'] = " ";
        $post_data['cus_city'] = " ";
        $post_data['cus_state'] = " ";
        $post_data['cus_postcode'] = " ";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
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

                session()->put('order_success', true);
                return redirect()->route('order-success');
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

            $this->stockService->restoreStock($payment->order_id);


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
