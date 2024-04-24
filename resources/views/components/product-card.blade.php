<div class="p-4 bg-white  rounded-lg hover:shadow-xl">
    <a href="{{ route('product.details', ['product' => $product->id]) }}">
        <img src="{{ asset('images/product.webp') }}" alt="" class="object-cover w-full h-40 mb-4 rounded-lg">
    </a>
    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
    <div class="flex justify-between">
        <p class="mt-2 text-xl font-semibold text-gray-800">${{ $product->price }}</p>
        <livewire:add-to-cart-button :productId="$product->id" :productName="$product->name" :productImage="''" :productPrice="$product->price"
            :productStock="$product->stock" />
    </div>
</div>
