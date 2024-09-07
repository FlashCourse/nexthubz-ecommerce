<x-app-layout>
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 items-start">
                <!-- Product Images -->
                <div x-data="{ mainImage: '{{ asset('storage/' . $product->image) }}' }" class="relative">
                    <!-- Main Image -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden shadow-md">
                        <a :href="mainImage">
                            <img :src="mainImage" alt="Product Image"
                                class="w-full h-[550px] object-cover transition-transform duration-300 hover:scale-105 cursor-pointer">
                        </a>
                    </div>
                    <!-- Thumbnails (Including Default Product Image) -->
                    <div class="flex space-x-4 mt-4 overflow-x-auto py-2">
                        <!-- Default Product Image Thumbnail -->
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Default Product Image"
                            class="w-24 h-24 p-1 border border-gray-200 rounded-lg object-cover cursor-pointer hover:border-primary hover:shadow-lg transition duration-200"
                            @click="mainImage = '{{ asset('storage/' . $product->image) }}'">
                        <!-- Variant Thumbnails -->
                        @foreach ($product->variants as $variant)
                            <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant Image"
                                class="w-24 h-24 p-1 border border-gray-200 rounded-lg object-cover cursor-pointer hover:border-primary hover:shadow-lg transition duration-200"
                                @click="mainImage = '{{ asset('storage/' . $variant->image) }}'">
                        @endforeach
                    </div>
                </div>

                <!-- Product Details -->
                <div class="flex flex-col justify-center bg-white p-8 shadow-lg rounded-lg">
                    <h2 class="text-3xl font-bold text-foreground mb-6">{{ $product->name }}</h2>
                    @if (!empty($product->short_description))
                        <div class="text-lg text-muted mb-4">
                            {{ $product->short_description }}
                        </div>
                    @endif
                    <livewire:product-variant-selector :product="$product" />
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Product Description -->
            <div class="bg-white border border-gray-200 rounded-lg p-8 mb-12 shadow-sm">
                <h3 class="text-2xl font-bold text-foreground mb-4">Product Description</h3>

                @if (!empty($product->description))
                    <div class="text-muted leading-relaxed">
                        {!! html_entity_decode($product->description, ENT_QUOTES, 'UTF-8') !!}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center text-center py-12">
                        <i class="fas fa-info-circle text-muted mb-4" style="font-size: 3rem;"></i>
                        <span class="text-lg text-gray-600 font-medium">No product description available.</span>
                    </div>
                @endif
            </div>


            <!-- Reviews Section -->
            <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
                <h3 class="text-2xl font-bold text-foreground mb-4">Reviews</h3>

                <livewire:product-review :productId="$product->id" />
            </div>
        </div>
    </section>
</x-app-layout>
