<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;

class Checkout extends Component
{

    public $errors;
    public $stockAvailable;

    // Shipping Address
    public $firstName;
    public $lastName;
    public $address1;
    public $address2;
    public $city;
    public $state;
    public $zipCode;
    public $country;
    public $phone;


    // Payment Method
    public $paymentMethod = '';

    public $cart = [];
    public $tax = 0.03;
    public $shipping = 30;
    public $subtotal = 0;
    public $total = 0;

    public $order_id; // database order id to store in the order for tracking order

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        if (empty($this->cart)) {
            // Redirect to the home page
            return Redirect::route('home');
        }
        // Store cart data in the cartData session
        session(['cartData' => $this->cart]);
        // Retrieve address data from session or set to empty string if not available
        $addressData = session('address', []);

        $this->firstName = $addressData['first_name'] ?? '';
        $this->lastName = $addressData['last_name'] ?? '';
        $this->address1 = $addressData['address1'] ?? '';
        $this->address2 = $addressData['address2'] ?? '';
        $this->city = $addressData['city'] ?? '';
        $this->state = $addressData['state'] ?? '';
        $this->zipCode = $addressData['zip_code'] ?? '';
        $this->country = $addressData['country'] ?? '';
        $this->phone = $addressData['phone'] ?? '';
        $this->calculateSubtotal();
        $this->calculateTotal();
        $this->checkAvailability();
    }


    public function checkAvailability()
    {
        // Retrieve cart items from state
        $cartItems = $this->cart;

        // Get all product IDs from the cart
        $productIds = array_column($cartItems, 'product_id');

        // Query the product table to fetch product information including stock
        $products = DB::table('products')
            ->whereIn('id', $productIds)
            ->select('id', 'name', 'stock')
            ->get();

        // Map product IDs to product data for easier access
        $productData = [];
        foreach ($products as $product) {
            $productData[$product->id] = $product;
        }

        $this->errors = [];

        // Check availability for each product in the cart
        $this->stockAvailable = true;

        foreach ($cartItems as $cartItem) {
            $productId = $cartItem['product_id'];
            $requestedQuantity = $cartItem['quantity'];

            // Retrieve product information
            $product = $productData[$productId] ?? null;

            // Check if the product exists and if it has sufficient stock
            if (!$product) {
                $this->stockAvailable = false;
                $this->errors[$productId] = 'Product not found.';
            } elseif ($product->stock < $requestedQuantity) {
                $this->stockAvailable = false;
                $this->errors[$productId] = 'Insufficient stock.';
            }
        }
    }

    public function save()
    {
        // Store address information to the database
        $address = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'country' => $this->country,
            'phone' => $this->phone,
        ];
        session(['addressData' => $address]);
        $orderData = [
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
        ];
        session(['orderData' => $orderData]);
        session()->forget('cart');


        if ($this->paymentMethod === 'cash') {
            return redirect()->route('cash-payment');
        } else if ($this->paymentMethod === 'bkash') {
            return redirect()->route('bkash-payment');
        } else {
            return redirect()->route('online-payment');
        }
    }

    public function calculateSubtotal()
    {
        $this->subtotal = collect($this->cart)->reduce(function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        });
    }

    public function calculateTotal()
    {
        $this->tax = $this->subtotal * $this->tax;
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
