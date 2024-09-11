@php
    $features = [
        [
            'icon' => 'fas fa-truck',
            'title' => 'Fast Shipping',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ],
        [
            'icon' => 'fas fa-hand-holding-heart',
            'title' => 'Excellent Customer Service',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ],
        [
            'icon' => 'fas fa-shield-alt',
            'title' => 'Secure Payments',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ],
    ];
@endphp

<section class="py-20 bg-primary text-light">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center mb-4">Why Choose Us</h2>
        <p class="text-lg text-center mb-10">Discover the benefits that set us apart and make us the preferred choice for
            your needs.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($features as $feature)
                <div class="flex items-center border hover:border-secondary border-transparent p-6 rounded-lg">
                    <div class="mr-4 bg-secondary text-light rounded-full p-4">
                        <i class="{{ $feature['icon'] }} text-4xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-light">{{ $feature['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
