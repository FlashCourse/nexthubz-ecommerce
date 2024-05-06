<x-app-layout>

    @include('partials.hero')
    @include('partials.best-selling')
    @include('partials.we-offer')


    {{-- Featured Products --}}
    <section class="py-8 px-4">
        <div class="mx-auto max-w-7xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-semibold mb-2">Featured Products</h2>
                <p class="text-gray-600">Check out our top picks</p>
            </div>
            <div class="grid grid-cols-1 gap-8 mx-auto max-w-7xl sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {{-- Displaying Livewire Product Cards --}}
                @foreach ($products as $product)
                    <x-product-card :product=$product />
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="#"
                    class="inline-block px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition duration-300">See
                    More</a>
            </div>
        </div>
    </section>

    {{-- Featured Categories --}}
<section class="py-12 px-4">
    <div class="mx-auto max-w-7xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold mb-2">Featured Categories</h2>
            <p class="text-gray-600">Explore our curated selection</p>
        </div>
        <!-- Add your category cards or content here -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach ($categories as $category)
            <div class="relative group">
                <div class="overflow-hidden text-center bg-white rounded-xl">
                    <a href="{{ route('product.search', ['categories' => $category->name]) }}" class="block relative overflow-hidden">
                        <div class="h-40 bg-cover bg-center rounded-md" style="background-image: url('{{ asset('/images/categories/' . $category->image) }}');" aria-label="{{ $category->name }}">
                            <div class="absolute inset-0 bg-orange-500 opacity-10 group-hover:opacity-80"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-white text-lg font-semibold hidden group-hover:block">{{ $category->name }}</span>
                            </div>
                        </div>
                    </a>
                    <span class="text-lg font-semibold group-hover:hidden">{{ $category->name }}</span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="#" class="inline-block px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition duration-300">See More</a>
        </div>
    </div>
</section>


    @include('partials.why-us');
    {{-- @include('partials.recent-post') --}}
    @include('partials.faq')




</x-app-layout>
