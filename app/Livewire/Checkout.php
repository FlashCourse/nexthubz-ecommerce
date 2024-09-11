<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Coupon;
use App\Services\SettingsService;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Validate;

class Checkout extends Component
{
    public $couponCode = '';
    public $discountAmount = 0;

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

    #[Validate('required|in:cash,bkash,card')]
    public $paymentMethod = '';

    public $cart = [];
    public $tax;
    public $shipping;
    public $subtotal = 0;
    public $total = 0;

    public $order_id;

    protected $settings;

    public function mount(SettingsService $settings)
    {
        $this->settings = $settings;
        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            return Redirect::route('home');
        }

        $address = Auth::check() ? Address::where('user_id', Auth::id())->first() : null;
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

        $this->calculateSubtotal();
        $this->calculateTax();
        $this->calculateShipping();
        $this->calculateTotal();

        $stockService = new StockService();
        list($this->stockAvailable, $this->stockErrors) = $stockService->checkAvailability($this->cart);
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
            'subtotal' => $this->subtotal,
            'total_discount' => $this->discountAmount,
            'tax' => $this->tax,
            'shipping_cost' => $this->shipping,
            'total' => $this->total,
        ];

        session(['orderData' => $orderData]);
        session()->forget('cart');
        session(['cartData' => $this->cart]);

        if ($this->paymentMethod === 'cash') {
            return redirect()->route('cash-payment');
        } elseif ($this->paymentMethod === 'bkash') {
            return redirect()->route('bkash-payment');
        } else {
            return redirect()->route('online-payment');
        }
    }

    public function calculateSubtotal()
    {
        $this->subtotal = collect($this->cart)->reduce(function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function calculateTax()
    {
        $taxRate = $this->settings->get('default_tax_rate') / 100;
        $discountedSubtotal = $this->subtotal - $this->discountAmount;
        $this->tax = $discountedSubtotal * $taxRate;
    }

    public function calculateShipping()
    {
        $this->shipping = $this->settings->get('flat_rate_shipping_active')
            ? $this->settings->get('flat_rate_amount')
            : 0;
    }

    public function calculateTotal()
    {
        $this->total = $this->subtotal - $this->discountAmount + $this->tax + $this->shipping;
    }

    public function applyCoupon()
    {
        $coupon = Coupon::where('code', $this->couponCode)->active()->first();

        if (!$coupon) {
            session()->flash('coupon_error', 'Invalid or expired coupon code.');
            $this->discountAmount = 0;
            $this->calculateTotal();
            return;
        }

        if (!$coupon->isValid()) {
            session()->flash('coupon_error', 'Coupon is not valid for use.');
            $this->discountAmount = 0;
            $this->calculateTotal();
            return;
        }

        if (!is_array($this->cart) || empty($this->cart)) {
            session()->flash('coupon_error', 'Cart is not properly initialized or is empty.');
            return;
        }

        $this->discountAmount = 0;

        foreach ($this->cart as &$item) {
            if ($coupon->isApplicableToProduct($item['product_id'])) {
                $discount = $coupon->calculateDiscountForProduct($item['price'], $item['product_id']);
                $this->discountAmount += $discount * $item['quantity'];
                $item['discount'] = $discount;
                $item['final_price'] = $item['price'] - $discount;
            } else {
                $item['discount'] = 0;
                $item['final_price'] = $item['price'];
            }
        }

        $this->calculateSubtotal();
        $this->calculateTotal();

        session()->flash('coupon_success', 'Coupon applied successfully!');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
