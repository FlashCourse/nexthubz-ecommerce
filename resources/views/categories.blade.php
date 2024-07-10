<x-app-layout>
    {{-- Featured Categories --}}
    <section class="py-12 px-4">
        <div class="mx-auto max-w-7xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-semibold mb-2">All Categories</h2>

            </div>
            <!-- Category Grid -->
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

            <!-- Pagination Links -->
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        </div>
    </section>
</x-app-layout>
