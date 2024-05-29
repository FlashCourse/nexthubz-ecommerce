<div class="p-4 bg-white border flex flex-col justify-between rounded-lg hover:shadow-xl">
    <a href="{{ route('product.details', ['product' => $product->id]) }}">
        <img src="{{ asset('images/products/' . $product->image) }}" alt=""
            class="object-cover w-full h-40 mb-4 rounded-lg">
    </a>
    <h3 class="text-lg font-semibold line-clamp-2">{{ $product->name }}</h3>
    <div class="flex justify-between">
        <p class="mt-2 text-xl font-semibold text-gray-800"><span class="text-lg font-extrabold">&#2547;</span>
            {{ $product->price }}</p>
    </div>
</div>
