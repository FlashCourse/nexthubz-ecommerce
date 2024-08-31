<?php

namespace App\Livewire;

use App\Models\Address;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Validate;

class Checkout extends Component
{

    public $stockErrors;
    public $stockAvailable;

    // Shipping Address
    #[Validate('required|string|max:255')]
    public $firstName = '';

    #[Validate('required|string|max:255')]
    public $lastName = '';

    #[Validate('required|string|max:255')]
    public $address1 = '';

    #[Validate('nullable|string|max:255')]
    public $address2 = '';

    #[Validate('required|string|max:255')]
    public $city = '';

    #[Validate('nullable|string|max:255')]
    public $state = '';

    #[Validate('required|string|max:20')]
    public $zipCode = '';

    #[Validate('required|string|max:255')]
    public $country = '';

    #[Validate('required|string|max:15')]
    public $phone = '';

    // #[Validate('nullable|string|max:15')]
    // public $couponCode = '';



    // Payment Method
    #[Validate('required|in:cash,bkash,online')]
    public $paymentMethod = '';

    public $cart = [];
    public $tax;
    public $shipping;
    public $subtotal = 0;
    public $total = 0;

    public $order_id; // database order id to store in the order for tracking order

    protected $settings;

    public function mount(SettingsService $settings)
    {
        $this->settings = $settings;
        $this->cart = session()->get('cart', []);
        if (empty($this->cart)) {
            // Redirect to the home page
            return Redirect::route('home');
        }
        // Store cart data in the cartData session
        session(['cartData' => $this->cart]);

        // Check if the user is authenticated
        if (Auth::check()) {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Retrieve the address data for the authenticated user
            $address = Address::where('user_id', $user->id)->first();
        } else {
            // Handle guest checkout: address data might be stored in session or other storage
            $address = null; // Or fetch guest address from session or other storage if applicable
        }

        // Check if the address is available, otherwise set to an empty array
        $addressData = $address ? $address->toArray() : [];

        $this->firstName = $addressData['first_name'] ?? '';
        $this->lastName = $addressData['last_name'] ?? '';
        $this->address1 = $addressData['address1'] ?? '';
        $this->address2 = $addressData['address2'] ?? '';
        $this->city = $addressData['city'] ?? '';
        $this->state = $addressData['state'] ?? '';
        $this->zipCode = $addressData['zip_code'] ?? '';
        $this->country = $addressData['country'] ?? '';
        $this->phone = $addressData['phone'] ?? '';


        // Set Shipping and Tax
        $this->tax = $this->settings->get('default_tax_rate') / 100;
        $this->shipping = $this->settings->get('flat_rate_shipping_active')
            ? $this->settings->get('flat_rate_amount')
            : 0;

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

        $this->stockErrors = [];

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
                $this->stockErrors[$productId] = 'Product not found.';
            } elseif ($product->stock < $requestedQuantity) {
                $this->stockAvailable = false;
                $this->stockErrors[$productId] = 'Insufficient stock.';
            }
        }
    }

    public function save()
    {
        $this->validate();
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
