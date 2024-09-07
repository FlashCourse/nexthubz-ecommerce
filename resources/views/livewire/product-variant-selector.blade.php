<div>
    <p class="mb-2">
        <span class="inline-block bg-muted text-foreground py-1 px-3 text-xs rounded-full">
            {{ $product->category->name }}
        </span>
    </p>

    @if ($variants->isNotEmpty())
        <div class="mb-4">
            <h2 class="mb-2 text-xl font-semibold text-foreground">Variants</h2>
            <div class="flex flex-wrap -m-2">
                @foreach ($variants as $variant)
                    <div wire:click="selectVariant({{ $variant->id }})"
                        class="m-2 cursor-pointer p-2 border rounded-lg select-none {{ $selectedVariant == $variant->id ? 'border-success bg-success/10' : 'border-muted' }} flex flex-col items-start">
                        <div class="mt-1 text-sm text-muted">
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
        <h2 class="text-xl font-black text-foreground">
            @if ($hasDiscount)
                <!-- Show sale price first and regular price with strikethrough -->
                <span class="text-price">&#2547; {{ number_format($selectedVariantPrice, 2) }}</span>
                <span class="line-through text-discount ml-2">&#2547;
                    {{ number_format($selectedVariantRegularPrice, 2) }}</span>
            @else
                <!-- Show only the regular price -->
                <span>&#2547; {{ number_format($selectedVariantPrice, 2) }}</span>
            @endif
        </h2>
    </div>

    <div class="mb-4">
        <div class="flex items-center">
            <!-- Calculate and Display Average Rating -->
            @php
                $averageRating = $product->reviews->count() > 0 ? $product->reviews->avg('rating') : 0;
            @endphp

            <!-- Display Stars Based on Average Rating -->
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $averageRating)
                    <i class="fas fa-star text-warning"></i> <!-- Full Star -->
                @elseif ($i - $averageRating < 1)
                    <i class="fas fa-star-half-alt text-warning"></i> <!-- Half Star -->
                @else
                    <i class="far fa-star text-warning"></i> <!-- Empty Star -->
                @endif
            @endfor

            <!-- Display the Average Rating as a Number -->
            <span class="ml-2 text-muted">{{ number_format($averageRating, 1) }} out of 5</span>
        </div>
    </div>


    <!-- Display success message -->
    <div x-data="{ show: @entangle('message').defer }" x-init="@this.on('productAddedToCart', () => {
        show = true;
        setTimeout(() => show = false, 3000)
    })" class="fixed left-5 top-20 z-50">
        <template x-if="show">
            <div class="bg-primary text-white px-2 py-2 w-40 flex items-center shadow-lg">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        </template>
    </div>

    <div class="flex items-center mb-4">
        @if ($variants->isNotEmpty())
            <!-- Check stock for the selected variant -->
            @if ($selectedVariant && $variants->find($selectedVariant)->stock > 0)
                <button wire:click="addToCart" class="py-2 px-4 bg-primary text-white rounded-md hover:bg-dark">
                    Add to Cart
                </button>
            @else
                <button disabled class="py-2 px-4 bg-gray-400 text-white rounded-md cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        @else
            <!-- Check stock for the product if no variants -->
            @if ($product->stock > 0)
                <button wire:click="addToCart" class="py-2 px-4 bg-primary text-white rounded-md hover:bg-dark">
                    Add to Cart
                </button>
            @else
                <button disabled class="py-2 px-4 bg-warning text-white rounded-md cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        @endif
    </div>
</div>
