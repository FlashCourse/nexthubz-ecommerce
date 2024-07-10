<x-app-layout>

    @include('partials.hero')


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
                    <div class="overflow-hidden bg-white rounded-xl border hover:shadow-lg text-center">
                        <a href="{{ route('product.search', ['categories' => $category->slug]) }}" class="block relative">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="w-16 h-16 mx-auto mt-8 object-cover rounded-t-xl">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800">{{ $category->name }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="#"
                    class="inline-block px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition duration-300">See
                    More</a>
            </div>
        </div>
    </section>


    @include('partials.best-selling')
    @include('partials.new-arrival')
    @include('partials.we-offer')
    @include('partials.discounted')

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
                <a href="/product/search"
                    class="inline-block px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition duration-300">See
                    More</a>
            </div>
        </div>
    </section>

    @include('partials.why-us');
    {{-- @include('partials.recent-post') --}}
    @include('partials.faq')




</x-app-layout>
