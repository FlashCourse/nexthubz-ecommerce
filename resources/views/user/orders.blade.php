<x-user-dashboard>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Order Listings') }}
        </h2>
    </x-slot>

    <section class="py-8">
        <div class="max-w-7xl mx-auto">
            {{-- Order Filters --}}
            <div class="mb-8">
                <label for="status" class="text-gray-600 block mb-2">Filter by Status:</label>
                <select id="status" name="status" class="py-2 focus:ring-0 border rounded-md">
                    <option value="all">All Orders</option>
                    <option value="pending">Pending</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>

            {{-- Order Listings --}}
            <div class="bg-white rounded-lg">
                <h2 class="text-2xl font-bold p-6 border-b">Order Listings</h2>

                <!-- Order Items -->
                <div class="p-6">
                    <!-- Loop through orders -->
                    @foreach ($orders as $order)
                        <div class="mb-4 border-b pb-4">
                            <div class="flex flex-wrap justify-between items-center">
                                <div class="mb-2 md:mr-6 w-full md:w-auto">
                                    <p class="text-gray-600">Order Date:</p>
                                    <p class="text-orange-500 font-bold">{{ $order->created_at->format('F d, Y') }}</p>
                                </div>
                                <div class="mb-2 md:mr-6 w-full md:w-auto">
                                    <p class="text-gray-600">Order Number:</p>
                                    <p class="text-orange-500 font-bold">{{ $order->order_number }}</p>
                                </div>
                                <div class="mb-2 md:mr-6 w-full md:w-auto">
                                    <p class="text-gray-600">Status:</p>
                                    <p class="text-orange-500 font-bold">{{ $order->status }}</p>
                                </div>
                                <div class="mb-2 md:mr-6 w-full md:w-auto">
                                    <p class="text-gray-600">Total Price:</p>
                                    <p class="text-orange-500 font-black">&#2547;{{ number_format($order->total, 2) }}
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('user.order.details', ['order' => $order->id]) }}"
                                        class="inline-flex items-center px-3 py-2 text-white bg-orange-500 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                        title="View Order Details">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-user-dashboard>
