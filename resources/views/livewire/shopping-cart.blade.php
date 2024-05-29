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

    <div x-show="open" x-cloak @click.outside="open = false"
        class="fixed top-0 right-0 z-50 h-full overflow-y-auto bg-white shadow-xl w-80">
        <div class="p-5 relative h-full">
            <div class="flex justify-between items-center pb-4 mb-4 text-lg font-semibold border-b border-gray-200">
                <h2>Shopping Cart ({{ count($cart) }} items)</h2>
                <button x-on:click="open = false" class="text-gray-600 hover:text-gray-800">X</button>
            </div>

            @if (count($cart) > 0)
                <ul class="space-y-4">
                    @foreach ($cart as $key => $item)
                        <li class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg shadow-md">
                            <div class="w-16 h-16 bg-gray-200 rounded-md overflow-hidden flex-shrink-0">
                                <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                    class="object-cover w-full h-full">
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $item['name'] }}</p>

                                    </div>
                                    <button wire:click="removeFromCart('{{ $key }}')"
                                        class="text-red-500 hover:text-red-700">
                                        X
                                    </button>
                                </div>
                                @if (isset($item['variant_attributes']) && is_array($item['variant_attributes']))
                                    <div class="flex flex-wrap gap-2 mt-1 text-xs">
                                        @foreach ($item['variant_attributes'] as $attribute => $value)
                                            <span
                                                class="bg-gray-200 text-gray-800 py-1 px-2 rounded">{{ $attribute }}:
                                                {{ $value }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex justify-between">

                                    <div class="flex flex-col">
                                        <p class="mt-2 text-xs  text-gray-800">&#2547;{{ $item['price'] }}</p>

                                        <p class=" text-sm font-semibold text-gray-800">
                                            &#2547;{{ $item['price'] * $item['quantity'] }}</p>
                                    </div>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <button wire:click="decreaseQuantity('{{ $key }}')"
                                            class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <p class="text-sm text-gray-800">{{ $item['quantity'] }}</p>
                                        <button wire:click="increaseQuantity('{{ $key }}')"
                                            class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-md text-center text-gray-600 mt-8">Your cart is empty.</p>
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
