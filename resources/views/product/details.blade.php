<x-app-layout>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 items-center">
                <!-- Product Image -->
                <div class="relative">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                        class="w-full h-[550px] p-8 border rounded-lg object-cover border">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-50 rounded-lg">
                    </div>
                </div>

                <!-- Product Details -->
                <div class="flex flex-col justify-center bg-white border rounded-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">{{ $product->name }}</h2>
                    <div class="text-lg text-gray-600 mb-4">
                        {{ $product->short_description }}
                    </div>
                    <div class="mb-6">
                        <span class="text-2xl font-bold text-gray-800">${{ $product->price }}</span>
                    </div>
                    <livewire:product-variant-selector :product="$product" />
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white border rounded-lg p-8 mb-12">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Product Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
            </div>
            <div class="bg-white border rounded-lg p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Reviews</h3>
                <livewire:product-review :productId="$product->id" />
            </div>
        </div>
    </section>

</x-app-layout>
