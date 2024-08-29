<div class="p-4 bg-white border flex flex-col justify-between rounded-lg hover:shadow-xl">
    <a href="{{ route('product.details', ['product' => $product->slug]) }}">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
            class="object-cover w-full h-40 mb-4 rounded-lg">
    </a>
    <h3 class="text-lg font-semibold line-clamp-2">{{ $product->name }}</h3>
    <div class="flex justify-between items-center mt-2">
        <p class="text-xl font-semibold text-gray-800">
            @if (isset($discount) && $discount < $product->price)
                <span class="text-lg font-extrabold">&#2547;</span><span class="line-through text-gray-500 mr-2">
                    {{ number_format($product->price, 2) }}</span>
                <span class="text-lg font-extrabold">&#2547; </span><span>{{ number_format($discount, 2) }}</span>
            @else
                {{ number_format($product->price, 2) }}
            @endif
        </p>
    </div>
</div>
