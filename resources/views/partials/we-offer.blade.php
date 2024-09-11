<section class="bg-primary text-light py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Discover Our Top Offers</h2>
        <p class="text-lg text-center mb-10">Explore our diverse range of high-quality products and services tailored to
            your needs.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $offers = [
                    [
                        'title' => 'Fresh Produce',
                        'description' => 'Farm-fresh fruits and vegetables sourced locally for the best quality.',
                        'icon' => 'fas fa-leaf',
                    ],
                    [
                        'title' => 'Groceries',
                        'description' => 'All your pantry essentials including grains, pulses, oils, and spices.',
                        'icon' => 'fas fa-shopping-basket',
                    ],
                    [
                        'title' => 'Household Essentials',
                        'description' =>
                            'From cleaning supplies to home appliances, we have everything you need to keep your home running smoothly.',
                        'icon' => 'fas fa-home',
                    ],
                    [
                        'title' => 'Personal Care',
                        'description' =>
                            'Take care of yourself with our wide range of skincare, haircare, and personal grooming products.',
                        'icon' => 'fas fa-user-circle',
                    ],
                ];
            @endphp

            @foreach ($offers as $offer)
                <div class="rounded-lg hover:shadow-2xl hover:cursor-pointer p-6 flex flex-col items-center">
                    <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mb-4">
                        <i class="{{ $offer['icon'] }} text-4xl text-light"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $offer['title'] }}</h3>
                    <p class="text-center">{{ $offer['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
