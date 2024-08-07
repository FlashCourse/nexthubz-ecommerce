<x-slot name="header">
    <h2 class="text-2xl font-bold leading-tight text-gray-800">
        {{ __('Checkout') }}
    </h2>
</x-slot>



<section class="py-8 px-4">
    <div class="mx-auto max-w-7xl">
        @if (session()->has('message'))
            <x-modal id="myModal" maxWidth="lg">
                <div class="p-4 bg-red-500 text-white text-center"> {{ session('message') }}</div>
            </x-modal>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="save">
            {{-- Shipping Address --}}
            <div class="p-6 mb-8 bg-white rounded-lg border">
                <h2 class="mb-4 text-xl font-semibold">Shipping Address</h2>
                <!-- Add your form fields for shipping address here -->

                <div class="grid grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div class="mb-4">
                        <label for="firstName" class="block text-sm font-medium text-gray-600">First Name</label>
                        <x-input type="text" wire:model="firstName" id="firstName" name="firstName"
                            value="{{ $firstName }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label for="lastName" class="block text-sm font-medium text-gray-600">Last Name</label>
                        <x-input type="text" wire:model="lastName" id="lastName" name="lastName"
                            value="{{ $lastName }}" class="w-full p-2 mt-1" />
                    </div>

                    <!-- Address Line 1 -->
                    <div class="col-span-2 mb-4">
                        <label for="address1" class="block text-sm font-medium text-gray-600">Address Line 1</label>
                        <x-input type="text" wire:model="address1" id="address1" name="address1"
                            value="{{ $address1 }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-span-2 mb-4">
                        <label for="address2" class="block text-sm font-medium text-gray-600">Address Line 2</label>
                        <x-input type="text" wire:model="address2" id="address2" name="address2"
                            value="{{ $address2 }}" class="w-full p-2 mt-1" />
                    </div>

                    <!-- City -->
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-600">City</label>
                        <x-input type="text" wire:model="city" id="city" name="city"
                            value="{{ $city }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- State -->
                    <div class="mb-4">
                        <label for="state" class="block text-sm font-medium text-gray-600">State</label>
                        <x-input type="text" wire:model="state" id="state" name="state"
                            value="{{ $state }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- ZIP Code -->
                    <div class="mb-4">
                        <label for="zipCode" class="block text-sm font-medium text-gray-600">ZIP Code</label>
                        <x-input type="text" wire:model="zipCode" id="zipCode" name="zipCode"
                            value="{{ $zipCode }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- Country -->
                    <div class="mb-4">
                        <label for="country" class="block text-sm font-medium text-gray-600">Country</label>
                        <x-input type="text" wire:model="country" id="country" name="country"
                            value="{{ $country }}" class="w-full p-2 mt-1" required />
                    </div>

                    <!-- Phone Number -->
                    <div class="col-span-2 mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-600">Phone Number</label>
                        <x-input type="tel" wire:model="phone" id="phone" name="phone"
                            value="{{ $phone }}" class="w-full p-2 mt-1" required />
                    </div>
                </div>

            </div>


            {{-- Payment Methods --}}
            <div class="p-6 mb-8 bg-white rounded-lg border">
                <h2 class="mb-4 text-xl font-semibold">Payment Method</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center p-4 bg-gray-100 rounded-md">
                        <input type="radio" wire:model="paymentMethod" id="cash" name="paymentMethod"
                            value="cash" class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
                        <label for="cash" class="ml-4 text-gray-600 hover:cursor-pointer flex items-center">
                            <i class="fas fa-money-bill-wave text-2xl text-orange-500 mr-2"></i>
                            <span>Cash on Delivery</span>
                        </label>
                    </div>
                    <div class="flex items-center p-4 bg-gray-100 rounded-md">
                        <input type="radio" wire:model="paymentMethod" id="bkash" name="paymentMethod"
                            value="bkash" class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
                        <label for="bkash" class="ml-4 text-gray-600 hover:cursor-pointer flex items-center">
                            <i class="fas fa-mobile-alt text-2xl text-orange-500 mr-2"></i>
                            <span>bKash</span>
                        </label>
                    </div>
                    <div class="flex items-center p-4 bg-gray-100 rounded-md">
                        <input type="radio" wire:model="paymentMethod" id="card" name="paymentMethod"
                            value="card" class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
                        <label for="card" class="ml-4 text-gray-600 hover:cursor-pointer flex items-center">
                            <i class="fas fa-credit-card text-2xl text-orange-500 mr-2"></i>
                            <span>Card Payment</span>
                        </label>
                    </div>
                </div>
            </div>





            {{-- Payment Summary --}}
            <div class="p-6  rounded-lg border">
                <h2 class="mb-4 text-xl font-semibold">Payment Summary</h2>

                @if (count($cart) > 0)
                    <!-- Itemized List -->
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">{{ count($cart) }} Items</h3>
                        <!-- Example: Display a list of items with quantities and prices -->
                        <div class="grid gap-4">
                            @foreach ($cart as $item)
                                <div class="p-4 bg-white rounded-lg flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ asset('storage/' . $item['image']) }}"
                                            alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                        <div class="ml-4">
                                            <h4 class="font-semibold">{{ $item['name'] }}</h4>
                                            <p class="text-sm text-gray-500">&#2547;{{ $item['price'] }} x
                                                {{ $item['quantity'] }}</p>
                                            @if (isset($item['variant_attributes']) && is_array($item['variant_attributes']))
                                                <div class="flex flex-wrap gap-1 mt-1 text-xs">
                                                    @foreach ($item['variant_attributes'] as $attribute => $value)
                                                        <span
                                                            class="inline-block bg-gray-200 text-gray-800 py-1 px-2 rounded">{{ $attribute }}:
                                                            {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if (isset($availability['errors'][$item['product_id']]))
                                                <p class="text-sm text-red-500">
                                                    {{ $availability['errors'][$item['product_id']] }}</p>
                                            @endif
                                            @if (isset($errors[$item['product_id']]))
                                                <p class="text-sm text-red-500">{{ $errors[$item['product_id']] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span
                                        class="font-semibold text-orange-500">&#2547;{{ $item['price'] * $item['quantity'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif



                <!-- Subtotal, Shipping, and Total -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-gray-600">Subtotal:</p>
                        <!-- Replace with dynamic data -->
                        <p class="font-semibold text-orange-500">&#2547;{{ $subtotal }}</p>
                    </div>

                    <!-- Additional Charges -->
                    <div class="mb-4">
                        <h3 class="mb-2 text-lg font-semibold">Additional Charges</h3>
                        <!-- Example: Display additional charges like taxes or shipping -->
                        <ul class="pl-4 list-disc">
                            <li class="flex justify-between">
                                <span>Tax</span>
                                <span class="text-orange-500">&#2547;{{ number_format($tax, 2) }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-orange-500">&#2547;{{ number_format($shipping, 2) }}</span>
                            </li>
                            <!-- Add more charges as needed -->
                        </ul>
                    </div>

                    <div class="flex items-center justify-between mb-2">
                        <p class="text-gray-600">Total:</p>
                        <!-- Replace with dynamic data -->
                        <p class="text-lg font-bold text-orange-500">&#2547;{{ number_format($total, 2) }}</p>
                    </div>
                </div>

                <!-- Place Order Button -->
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
