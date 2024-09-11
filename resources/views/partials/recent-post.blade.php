@php
    $posts = [
        [
            'title' => '10 Delicious Recipes for Seasonal Fruits',
            'subtitle' => 'Explore these mouthwatering recipes featuring fresh fruits in season.',
            'image' => '/images/post-2.jpg',
            'link' => '#',
        ],
        [
            'title' => '5 Easy Steps to Reduce Food Waste at Home',
            'subtitle' => 'Discover simple yet effective strategies to minimize food waste and save money.',
            'image' => '/images/post-3.jpg',
            'link' => '#',
        ],
        [
            'title' => 'How to Store Vegetables to Keep Them Fresh',
            'subtitle' => 'Learn the best ways to store your veggies to prolong their freshness and flavor.',
            'image' => '/images/post-1.jpg',
            'link' => '#',
        ],
    ];
@endphp

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        {{-- title --}}
        <h2 class="text-3xl font-semibold text-primary text-center mb-4">Recent Posts</h2>
        {{-- subtitle --}}
        <p class="text-muted text-center mb-8">Check out our latest articles</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-light rounded-lg hover:shadow-2xl group">
                    <div class="overflow-hidden">
                        <img src="{{ asset($post['image']) }}" alt="Post Image"
                            class="w-full h-80 ease-in-out duration-300 group-hover:scale-110 object-cover rounded-t-lg">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl text-primary font-semibold mb-2 group-hover:underline">{{ $post['title'] }}
                        </h3>
                        <p class="text-muted mb-4">{{ $post['subtitle'] }}</p>
                        <a href="{{ $post['link'] }}" class="text-primary hover:underline">Read more</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-12">
            <a href="#"
                class="border-2 border-primary text-xl text-primary hover:bg-primary hover:text-white hover:border-green-0 px-4 py-3 rounded-md">Read
                more post <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
</section>
