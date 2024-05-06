<x-app-layout>
    <section class="py-8 px-4">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                {{-- Product Image --}}
                <div>
                    <img src="{{asset('/images/products/' . $product->image)}}" alt="Product Image" class="w-full h-[550px] border rounded-lg object-cover">
                </div>
    
                {{-- Product Details --}}
                <div class="flex flex-col justify-center">
                    <h1 class="mb-4 text-3xl font-bold">{{ $product->name }}</h1>
                    <p class="mb-2">  <span class="inline-block bg-gray-200 text-gray-800 py-1 px-3 text-xs rounded-full">{{ $product->category->name }}</span></p>
                    <p class="mb-4 text-gray-600">{{ $product->description }}</p>
    
                    <div class="flex items-center mb-4">
                        @if ($product->discount > 0)
                            @php
                                $discountedPrice = number_format(
                                    $product->price - $product->price * ($product->discount / 100),
                                    2,
                                );
                            @endphp
                            <span class="mr-2 text-xl font-bold text-green-500">{{ $discountedPrice }}</span>
                            <span class="text-gray-500 line-through">&#2547;{{ $product->price }}</span>
                        @else
                            <span class="mr-2 text-xl font-bold text-gray-800">&#2547;{{ $product->price }}</span>
                        @endif
                    </div>
    
                    {{-- Add to Cart button --}}
                    <livewire:add-to-cart-button :productId="$product->id" :productName="$product->name" :productImage="$product->image" :productPrice="$product->price" :productStock="$product->stock" />
                </div>
            </div>
        </div>
    </section>
    
    

    <section class="py-8">
        <div class="mx-auto max-w-7xl">
            <livewire:product-review :productId="$product->id" />
        </div>
    </section>
</x-app-layout>
