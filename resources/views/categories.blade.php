<x-app-layout>
    {{-- Featured Categories --}}
    <section class="px-4 py-12">
        <div class="mx-auto max-w-7xl flex" x-data="{ activeCategory: '{{ $categories->firstWhere('parent_id', null)->slug ?? '' }}' }">
            <!-- Root Categories (Left Side) -->
            <div class="w-1/4 md:w-1/5 lg:w-1/6 pr-4 sticky top-4 h-screen overflow-y-auto">
                <div class="space-y-6">
                    @foreach ($categories->where('parent_id', null) as $category)
                        <!-- Display root categories -->
                        <div class="text-center">
                            <!-- Image Icon and Name -->
                            <a href="#" class="block focus:outline-none"
                                @click.prevent="activeCategory = '{{ $category->slug }}'">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="w-12 h-12 mx-auto mb-2">
                                <span class="text-sm font-medium text-foreground">{{ $category->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Child Categories (Right Side) -->
            <div class="flex-1 mx-2">
                @foreach ($categories->where('parent_id', null) as $category)
                    <!-- Display child categories for each root -->
                    <div x-show="activeCategory === '{{ $category->slug }}'" class="category-group">
                        <div class="flex flex-wrap gap-5">
                            @foreach ($category->children as $childCategory)
                                <!-- Display child category -->
                                <div class="bg-light rounded-xl border hover:shadow-lg text-center p-4 w-full">
                                    <h3 class="text-sm text-left font-medium text-foreground">{{ $childCategory->name }}
                                    </h3>

                                    @if ($childCategory->children->isNotEmpty())
                                        <!-- Display nested child categories under each child category -->
                                        <div class="mt-2 text-left pl-2">
                                            <ul class="list-disc list-inside">
                                                @foreach ($childCategory->children as $nestedChildCategory)
                                                    <li>
                                                        @if ($nestedChildCategory->children->isNotEmpty())
                                                            <!-- Nested child category with children -->
                                                            <a href="#" class="focus:outline-none"
                                                                @click.prevent="activeCategory = '{{ $nestedChildCategory->slug }}'">
                                                                {{ $nestedChildCategory->name }}
                                                            </a>
                                                        @else
                                                            <!-- Nested child category without children -->
                                                            <a
                                                                href="{{ route('product.search', ['categories' => $nestedChildCategory->slug]) }}">
                                                                {{ $nestedChildCategory->name }}
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
