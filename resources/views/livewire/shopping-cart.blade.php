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
                        <li class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center space-x-2">
                                <div class="flex-shrink-0 w-16 h-16 overflow-hidden bg-gray-200 rounded-md">
                                    <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                        class="object-cover w-full h-full">
                                </div>
                                <div>
                                    <p class="text-lg font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm"><span class="text-md font-extrabold">&#2547;
                                        </span>{{ $item['price'] }} x {{ $item['quantity'] }} =
                                        <span class="text-md font-extrabold">&#2547;
                                        </span>{{ $item['price'] * $item['quantity'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="increaseQuantity({{ $productId }})"
                                    class="px-3 py-1 text-white bg-green-500 rounded hover:bg-green-600">+</button>
                                <button wire:click="decreaseQuantity({{ $productId }})"
                                    class="px-3 py-1 text-white bg-yellow-500 rounded hover:bg-yellow-600">-</button>
                                <button wire:click="removeFromCart({{ $productId }})"
                                    class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">x</button>
                            </div>
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
