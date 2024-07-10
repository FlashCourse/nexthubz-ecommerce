<x-app-layout>
    <section class="px-4">
        <div class="container mx-auto py-8">
            <h1 class="text-3xl text-center font-semibold mb-4">Review Order Items</h1>

            <div class="grid grid-cols-1 xl:grid-cols-6 gap-5">
                <!-- Cart Items -->
                <div class="bg-white border xl:col-span-4 rounded-lg">
                    <h2 class="text-lg font-semibold border-b border-gray-200 py-4 px-6">Cart Items</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="py-4 px-6 text-left">Item</th>
                                    <th class="py-4 px-6 text-left">Unit Price</th>
                                    <th class="py-4 px-6 text-left">Quantity</th>
                                    <th class="py-4 px-6 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartData as $cartItem)
                                    <tr class="border-b border-gray-200">
                                        <td class="py-4 px-6">{{ $cartItem['name'] }} @if (isset($cartItem['variant_attributes']) && is_array($cartItem['variant_attributes']))
                                                <div class="flex flex-wrap gap-1 mt-1 text-xs">
                                                    @foreach ($cartItem['variant_attributes'] as $attribute => $value)
                                                        <span
                                                            class="inline-block bg-gray-200 text-gray-800 py-1 px-2 rounded">{{ $attribute }}:
                                                            {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">${{ number_format($cartItem['price'], 2) }}</td>
                                        <td class="py-4 px-6">{{ $cartItem['quantity'] }}</td>
                                        <td class="py-4 px-6">
                                            ${{ number_format($cartItem['price'] * $cartItem['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="xl:col-span-2 p-4 border">
                    <!-- Order Summary -->
                    <div class="rounded-lg mb-8">
                        <h2 class="text-lg font-semibold border-b border-gray-200 py-4 px-6">Order Summary</h2>
                        <div class="grid grid-cols-2 gap-4 px-6 py-4">
                            <div class="text-gray-600">Subtotal:</div>
                            <div class="text-right">${{ number_format($orderData['subtotal'], 2) }}</div>
                            <div class="text-gray-600">Tax:</div>
                            <div class="text-right">${{ number_format($orderData['tax'], 2) }}</div>
                            <div class="text-gray-600">Shipping:</div>
                            <div class="text-right">${{ number_format($orderData['shipping'], 2) }}</div>
                            <div class="text-xl font-semibold">Total:</div>
                            <div class="text-xl font-semibold text-right">${{ number_format($orderData['total'], 2) }}
                            </div>
                        </div>
                    </div>
                    <!-- Payment Reminder -->
                    <div class="text-center text-sm text-green-600 mb-4">
                        <p>Stay tuned for our confirmation call! Within 30 minutes of placing your order, our team will
                            be in touch.</p>
                    </div>


                    <!-- Confirmation Button -->
                    <div class="text-center">
                        <form action="{{ route('pay-cash') }}" method="POST">
                            @csrf
                            <x-button type='submit'>Confirm Order</x-button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>
</x-app-layout>
