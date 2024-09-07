@props(['categories'])

<div x-data="{ open: false, activeCategory: null }" class="relative">
    <!-- Dropdown Trigger -->
    <div @click="open = !open" class="cursor-pointer">
        <button class="px-4 py-2 text-gray-800 rounded">
            <i class="fas fa-cubes"></i>
            Categories
        </button>
    </div>

    <!-- Dropdown Menu -->
    <div x-show="open"
        class="absolute left-0 mt-2 bg-white border rounded shadow-md min-w-max max-h-[80vh] overflow-y-auto"
        @click.away="open = false" style="display: none;">
        <div class="flex w-auto">
            <!-- Main Categories -->
            <div class="bg-foreground text-light p-4">
                @foreach ($categories as $category)
                    <a href="{{ route('product.search', ['categories' => $category->slug]) }}"
                        @mouseenter="activeCategory = {{ $category->id }}"
                        class="p-2 rounded-md cursor-pointer hover:bg-gray-200 hover:text-primary flex items-center">
                        @if ($category->icon)
                            <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon"
                                class="w-5 h-5 mr-2">
                        @endif
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Child Categories -->
            <div class="bg-white p-4 border-l border-gray-200 w-96">
                @foreach ($categories as $category)
                    <div x-show="activeCategory === {{ $category->id }}">
                        <div class="font-semibold text-gray-700 mb-2">{{ $category->name }}</div>

                        <!-- Display the first level of children in a group -->
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($category->children as $child)
                                <div class="p-2 bg-gray-100 rounded-md">
                                    <a href="{{ route('product.search', ['categories' => $child->slug]) }}"
                                        class="font-semibold text-gray-600 mb-1">{{ $child->name }}</a>

                                    <!-- Display the second level of children (grandchildren) in a nested group -->
                                    @if ($child->children->count() > 0)
                                        <div class="grid grid-cols-1 gap-2 mt-2">
                                            @foreach ($child->children as $subChild)
                                                <a href="{{ route('product.search', ['categories' => $subChild->slug]) }}"
                                                    class="block px-2 py-1 text-gray-600 hover:bg-gray-200 rounded">{{ $subChild->name }}</a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-gray-500 text-sm">No further subcategories</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
