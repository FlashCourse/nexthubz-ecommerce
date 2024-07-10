<x-user-dashboard>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <section class="py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Order Status -->
            <div class="p-4">
                <!-- Timeline items -->
                <div class="flex justify-between items-center">
                    <!-- Pending -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-12 h-12 -mx-2 rounded-full {{ $statusColors['pending'] }} flex items-center justify-center z-10 relative">
                            <i class="fas fa-clock text-white text-2xl" title="Pending"></i>
                        </div>
                    </div>
                    <div class="grow {{ $statusColors['pending'] }} h-4"></div>

                    <!-- Processing -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-12 h-12 -mx-2 rounded-full {{ $statusColors['processing'] }} flex items-center justify-center z-10 relative">
                            <i class="fas fa-sync-alt text-white text-2xl" title="Processing"></i>
                        </div>
                    </div>
                    <div class="grow {{ $statusColors['processing'] }} h-4"></div>

                    <!-- Shipped -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-12 h-12 -mx-2 rounded-full {{ $statusColors['shipped'] }} flex items-center justify-center relative">
                            <i class="fas fa-truck text-white text-2xl" title="Shipped"></i>
                        </div>
                    </div>
                    <div class="grow {{ $statusColors['shipped'] }} h-4"></div>

                    <!-- Delivered -->
                    <div class="flex flex-col items-center">
                        <div
                            class="w-12 h-12 -mx-2 rounded-full {{ $statusColors['delivered'] }} flex items-center justify-center relative">
                            <i class="fas fa-check-circle text-white text-2xl" title="Delivered"></i>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between">
                    <p>Pending</p>
                    <p>Processing</p>
                    <p>Shipped</p>
                    <p>Delivered</p>
                </div>
            </div>

            {{-- Order Details --}}
            <div class="bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold p-6 border-b">Order Details</h2>

                <!-- Order Date and ID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 md:gap-x-8 p-6 border-b">
                    <div>
                        <p class="text-gray-600">Order Date:</p>
                        <p class="text-orange-500 font-bold">{{ $order->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order ID:</p>
                        <p class="text-orange-500 font-bold">{{ $order->id }}</p>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 md:gap-x-8 p-6 border-b">
                    <div>
                        <p class="text-gray-600">Shipping:</p>
                        <p class="text-orange-500 font-bold">${{ number_format($order->shipping, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tax:</p>
                        <p class="text-orange-500 font-bold">${{ number_format($order->tax, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Subtotal:</p>
                        <p class="text-orange-500 font-bold">${{ number_format($order->subtotal, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Price:</p>
                        <p class="text-orange-500 font-bold">${{ number_format($order->total, 2) }}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Order Items List --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold p-6 border-b">Order Items</h2>
                <div class="p-6">
                    <ul class="divide-y divide-gray-200">
                        <!-- Loop through order items -->
                        @foreach ($order->orderItems as $item)
                            <li class="flex justify-between items-center py-4 border-b border-gray-200">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-md">
                                <div class="ml-4">
                                    <div class="text-lg font-medium">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->product->description }}</div>
                                    @if ($item->variant)
                                        <div class="text-sm text-gray-500">SKU: {{ $item->variant->sku }}</div>
                                        <div class="text-sm text-gray-500">
                                            @foreach ($item->variant->variantAttributes as $attribute)
                                                <span>{{ $attribute->attribute->name }}:
                                                    {{ $attribute->attributeValue->value }}</span><br>
                                            @endforeach
                                        </div>
                                    @else
                                        {{-- <h3>SKU: {{ $item->product->sku }}</h3> --}}
                                    @endif
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-orange-500 font-medium">${{ number_format($item->price, 2) }}
                                    </div>
                                    <div class="text-gray-400 mt-1">Quantity: {{ $item->quantity }}</div>
                                    <div class="text-orange-500 font-medium mt-1">
                                        ${{ number_format($item->price * $item->quantity, 2) }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-user-dashboard>
