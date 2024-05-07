<div x-data="{ open: @entangle('open') }">

    <button x-on:click="open = true" class="relative flex flex-col items-center">
        <span
            class="absolute inline-flex items-center justify-center w-6 h-6 -mt-2 -mr-2 text-white bg-red-500 rounded-full -top-1 -right-1">{{ count($cart) }}</span>
        <i class="fas fa-shopping-cart"></i>
        @if ($title)
            <p class="text-xs"> {{ $title }}</p>
        @endif
    </button>

    <div wire:loading class="fixed">
        <div class="fixed top-0 left-0 w-full h-full flex items-center  justify-center z-50">
            <i class="fas fa-spinner fa-3x text-orange-500 animate-spin"></i>
        </div>
    </div>

    <div x-show="open" x-cloak @click.outside="open=false"
        class="fixed top-0 right-0 z-50 h-full overflow-y-auto text-gray-600 bg-white shadow-lg w-96">
        <div class="p-4 relative h-full">
            <div class="flex justify-between pb-2 mb-4 text-xl font-semibold border-b">
                <h2>Shopping Cart ({{ count($cart) }} items)</h2>
                <button x-on:click="open = false">X</button>
            </div>

            @if (count($cart) > 0)
                <ul>
                    @foreach ($cart as $productId => $item)
                    <li class="border-b border-gray-200 py-2 px-4 flex items-center justify-between">
                        <!-- Product Image -->
                        <div class="flex-shrink-0 w-12 h-12 overflow-hidden bg-gray-200 rounded-md">
                            <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                class="object-cover w-full h-full">
                        </div>
                        <!-- Product Details -->
                        <div class="flex flex-col flex-1 ml-2">
                            <div>
                                <p class="text-sm font-semibold">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-600">&#2547;{{ $item['price'] }}</p>
                            </div>
                            <div class="flex items-center mt-1">
                                <button wire:click="decreaseQuantity({{ $productId }})"
                                    class="px-2 py-1 text-sm text-white bg-orange-500 rounded-md hover:bg-orange-600">-</button>
                                <p class="w-4 mx-2 text-sm">{{ $item['quantity'] }}</p>
                                <button wire:click="increaseQuantity({{ $productId }})"
                                    class="px-2 py-1 text-sm text-white bg-green-500 rounded-md hover:bg-green-600">+</button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">&#2547;{{ $item['price'] * $item['quantity'] }}</p>
                        </div>
                        <!-- Remove Button -->
                        <button wire:click="removeFromCart({{ $productId }})"
                            class="px-2 py-1 text-xs hover:text-white rounded-full hover:bg-red-600">X</button>
                    </li>
                    
                    
                    @endforeach
                </ul>
            @else
                <p class="text-lg">Your cart is empty.</p>
            @endif

            <div class="flex justify-center text-center p-4">
                <a href="{{ $totalPrice > 0 ? route('checkout') : '#' }}"
                    class="bg-orange-800 left-2 right-2 text-white absolute bottom-2 hover:bg-orange-900 font-bold py-2 px-4 rounded">
                    <span
                        class="inline-block text-lg">Checkout{{ $totalPrice > 0 ? ' (Total &#2547;' . $totalPrice . ')' : '' }}</span>


                </a>
            </div>
        </div>
    </div>

</div>
