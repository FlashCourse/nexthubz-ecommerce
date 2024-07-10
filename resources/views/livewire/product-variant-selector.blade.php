<div>
    <p class="mb-2">
        <span
            class="inline-block bg-gray-200 text-gray-800 py-1 px-3 text-xs rounded-full">{{ $product->category->name }}</span>
    </p>

    @if ($variants->isNotEmpty())
        <div class="mb-4">
            <h2 class="mb-2 text-xl font-semibold">Variants</h2>
            <div class="flex flex-wrap -m-2">
                @foreach ($variants as $variant)
                    <div wire:click="selectVariant({{ $variant->id }})"
                        class="m-2 cursor-pointer p-2 border rounded-lg select-none {{ $selectedVariant == $variant->id ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }} flex flex-col items-start">
                        <div class="mt-1 text-sm text-gray-600">
                            @foreach ($variant->variantAttributes->sortBy('attribute.name') as $attribute)
                                <span class="block">{{ $attribute->attribute->name }}:
                                    {{ $attribute->attributeValue->value }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-4">
        <h2 class="text-xl font-semibold">Price: ${{ $selectedVariantPrice }}</h2>
    </div>

    <div class="mb-4">
        <div class="flex items-center">
            <!-- Full stars -->
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
            <i class="fas fa-star text-yellow-500"></i>
        </div>
    </div>


    <div x-data="{ show: @entangle('message').defer }" x-init="@this.on('productAddedToCart', () => {
        show = true;
        setTimeout(() => show = false, 3000)
    })" class="fixed left-5 top-20 z-50">
        <template x-if="show">
            <div class="bg-orange-500 text-white px-2 py-2 w-40 flex items-center shadow-lg">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        </template>
    </div>

    <div class="flex items-center mb-4">
        <button wire:click="addToCart" class="py-2 px-4 bg-orange-800 text-white rounded-md hover:bg-orange-900">
            Add to Cart
        </button>
    </div>
</div>
