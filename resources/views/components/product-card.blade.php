<div class="p-4 bg-white border flex flex-col justify-between rounded-lg hover:shadow-xl">
    <a href="{{ route('product.details', ['product' => $product->slug]) }}">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
            class="object-cover w-full h-40 mb-4 rounded-lg">
    </a>
    <h3 class="text-lg font-semibold line-clamp-2">{{ $product->name }}</h3>
    <div class="flex justify-between items-center mt-2">
        <p class="text-xl font-semibold text-gray-800">
            @if ($product->sale_price < $product->regular_price)
                <!-- Show sale price first and regular price with strikethrough -->
                <span class="text-lg font-extrabold text-red-500">&#2547;
                </span><span>{{ number_format($product->sale_price, 2) }}</span>
                <span class="font-extrabold text-gray-500 ml-2">
                    <span class="line-through">{{ number_format($product->regular_price, 2) }}</span>
                </span>
            @else
                <!-- Show only the regular price if no sale is active -->
                <span class="text-lg font-extrabold">&#2547;
                </span><span>{{ number_format($product->regular_price, 2) }}</span>
            @endif
        </p>
    </div>
</div>
