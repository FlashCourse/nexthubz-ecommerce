<x-slot name="header">
    <h2 class="text-2xl font-bold leading-tight text-gray-800">
        {{ __('Checkout') }}
    </h2>
</x-slot>

<section class="py-8 px-4">
    <div class="mx-auto max-w-7xl">
        @if (session()->has('message'))
            <x-modal id="myModal" maxWidth="lg">
                <div class="p-4 bg-red-500 text-white text-center">{{ session('message') }}</div>
            </x-modal>
        @endif

        @if (session()->has('success'))
            <div
                class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            {{-- Shipping Address --}}
            <div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
                <h2 class="mb-4 text-xl font-semibold">Shipping Address</h2>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Shipping Form Fields -->
                    @foreach ([
        'firstName' => 'First Name',
        'lastName' => 'Last Name',
        'address1' => 'Address Line 1',
        'address2' => 'Address Line 2',
        'city' => 'City',
        'state' => 'State',
        'zipCode' => 'ZIP Code',
        'country' => 'Country',
        'phone' => 'Phone Number',
    ] as $field => $label)
                        <div class="{{ $field === 'address2' ? 'col-span-2' : '' }} mb-4">
                            <label for="{{ $field }}"
                                class="block text-sm font-medium text-gray-600">{{ $label }}</label>
                            <x-input type="text" wire:model="{{ $field }}" id="{{ $field }}"
                                name="{{ $field }}" class="w-full p-2 mt-1 border-gray-300 rounded-md" />
                            @error($field)
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
                <h2 class="mb-4 text-xl font-semibold">Payment Method</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ([
        'cash' => ['icon' => 'fas fa-money-bill-wave', 'label' => 'Cash on Delivery'],
        'bkash' => ['icon' => 'fas fa-mobile-alt', 'label' => 'bKash'],
        'card' => ['icon' => 'fas fa-credit-card', 'label' => 'Card Payment'],
    ] as $method => $info)
                        <div class="flex items-center p-4 bg-gray-100 rounded-md cursor-pointer">
                            <input type="radio" wire:model="paymentMethod" id="{{ $method }}"
                                name="paymentMethod" value="{{ $method }}"
                                class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
                            <label for="{{ $method }}" class="ml-4 text-gray-600 flex items-center">
                                <i class="{{ $info['icon'] }} text-2xl text-orange-500 mr-2"></i>
                                <span>{{ $info['label'] }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('paymentMethod')
                    <span class="text-red-500 text-center">{{ $message }}</span>
                @enderror
            </div>

            {{-- Coupon Code --}}
            <div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
                <h2 class="mb-4 text-xl font-semibold">Apply Coupon</h2>
                <div class="flex items-center">
                    <x-input type="text" wire:model.lazy="couponCode" placeholder="Enter coupon code"
                        class="w-full p-2 border border-gray-300 rounded-l-md" />
                    <button type="button" wire:click="applyCoupon"
                        class="bg-orange-500 text-white px-4 py-2 rounded-r-md hover:bg-orange-600 focus:outline-none">
                        Apply
                    </button>
                </div>
                <div>
                    @if (session()->has('success'))
                        <div
                            class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div
                            class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="p-6 bg-white rounded-lg border shadow-sm">
                <h2 class="mb-4 text-xl font-semibold">Payment Summary</h2>

                @if (count($cart) > 0)
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">{{ count($cart) }} Items</h3>
                        <div class="grid gap-4">
                            @foreach ($cart as $item)
                                <div class="p-4 bg-white rounded-lg flex items-center justify-between shadow-sm">
                                    <div class="flex items-center">
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                            class="w-16 h-16 object-cover rounded">
                                        <div class="ml-4">
                                            <h4 class="font-bold">{{ $item['name'] }}</h4>
                                            <p class="text-sm text-gray-500 line-through">
                                                &#2547;{{ number_format($item['price'], 2) }}</p>
                                            <p class="text-sm font-bold text-orange-500">
                                                &#2547;{{ number_format($item['final_price'] ?? $item['price'], 2) }} x
                                                {{ $item['quantity'] }}
                                            </p>
                                            @if (isset($item['discount']) && $item['discount'] > 0)
                                                <p class="text-sm text-gray-500">Discount:
                                                    &#2547;{{ number_format($item['discount'], 2) }} per item</p>
                                            @endif
                                            @if (isset($item['variant_attributes']) && is_array($item['variant_attributes']))
                                                <div class="flex flex-wrap gap-1 mt-1 text-xs">
                                                    @foreach ($item['variant_attributes'] as $attribute => $value)
                                                        <span
                                                            class="inline-block bg-gray-200 text-gray-800 py-1 px-2 rounded">{{ $attribute }}:
                                                            {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (isset($stockErrors[$item['product_id']]))
                                                <p class="text-sm text-red-500">{{ $stockErrors[$item['product_id']] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-orange-500">
                                            &#2547;{{ number_format(($item['final_price'] ?? $item['price']) * $item['quantity'], 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-gray-600">Subtotal:</p>
                        <p class="font-bold text-orange-500">&#2547;{{ number_format($subtotal, 2) }}</p>
                    </div>
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">Additional Charges</h3>
                        <ul class="pl-4 list-disc">
                            <li class="flex justify-between">
                                <span>Tax</span>
                                <span class="text-orange-500 font-bold">&#2547;{{ number_format($tax, 2) }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-orange-500 font-bold">&#2547;{{ number_format($shipping, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">Discounts</h3>
                        <ul class="pl-4 list-disc">
                            <li class="flex justify-between">
                                <span>Total Discount</span>
                                <span
                                    class="text-orange-500 font-bold">&#2547;{{ number_format($discountAmount, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-gray-600">Total:</p>
                        <p class="text-lg font-bold text-orange-500">&#2547;{{ number_format($total, 2) }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="w-full px-4 py-2 text-white transition duration-300 rounded-full
                        {{ $stockAvailable ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-400 cursor-not-allowed' }}"
                        {{ $stockAvailable ? '' : 'disabled' }}>
                        Place Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
