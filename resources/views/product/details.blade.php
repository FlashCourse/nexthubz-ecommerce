<x-app-layout>
    <section class="py-8 px-4">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                {{-- Product Image --}}
                <div>
                    <img src="{{ asset('/images/products/' . $product->image) }}" alt="Product Image"
                        class="w-full h-[550px] border rounded-lg object-cover">
                </div>

                {{-- Product Details --}}
                <div class="flex flex-col justify-center">
                    <livewire:product-variant-selector :product="$product" />
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
