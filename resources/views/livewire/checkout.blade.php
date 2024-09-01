<x-slot name="header">
    <h2 class="text-2xl font-bold leading-tight text-gray-800">
        {{ __('Checkout') }}
    </h2>
</x-slot>

<section class="py-8 px-4">
    <div class="mx-auto max-w-7xl">
        <div class="mb-4">
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
        </div>

        <form wire:submit.prevent="save">
            @include('livewire.partials.shipping-address-form')
            @include('livewire.partials.payment-method-form')
            @include('livewire.partials.coupon-code-form')

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
                                            @if (isset($item['discount']) && $item['discount'] > 0)
                                                <p class="text-sm text-gray-500 line-through">
                                                    &#2547;{{ number_format($item['price'], 2) }}
                                                </p>
                                                <p class="text-sm font-black text-orange-500">
                                                    &#2547;{{ number_format($item['final_price'], 2) }} x
                                                    {{ $item['quantity'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">Discount:
                                                    &#2547;{{ number_format($item['discount'], 2) }} per item
                                                </p>
                                            @else
                                                <p class="text-sm font-black text-orange-500">
                                                    &#2547;{{ number_format($item['price'], 2) }} x
                                                    {{ $item['quantity'] }}
                                                </p>
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
                                        <p class="font-black text-orange-500">
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
                        <p class="font-black text-orange-500">&#2547;{{ number_format($subtotal, 2) }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">Additional Charges</h3>
                        <ul class="pl-4 list-disc">
                            <li class="flex justify-between">
                                <span>Tax</span>
                                <span class="text-orange-500 font-black">+ &#2547;{{ number_format($tax, 2) }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-orange-500 font-black">+
                                    &#2547;{{ number_format($shipping, 2) }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Conditional Discounts Section -->
                    @if ($discountAmount > 0)
                        <div class="mb-4">
                            <h3 class="mb-2 text-lg font-semibold">Discounts</h3>
                            <ul class="pl-4 list-disc">
                                <li class="flex justify-between">
                                    <span>Total Discount</span>
                                    <span class="text-orange-500 font-black">-
                                        &#2547;{{ number_format($discountAmount, 2) }}</span>
                                </li>
                            </ul>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-2">
                        <p class="text-gray-600">Total:</p>
                        <p class="text-lg font-black text-orange-500">&#2547;{{ number_format($total, 2) }}</p>
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
