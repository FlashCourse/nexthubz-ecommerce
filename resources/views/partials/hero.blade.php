<!-- Check if there are any slides available -->
@if ($slides->isNotEmpty())
    <section class="py-20 text-gray-800 bg-orange-100 relative overflow-x-hidden px-4">
        <div class="swiper-container hero-swiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <!-- Dynamic Slide -->
                    <div class="swiper-slide">
                        <div class="max-w-7xl mx-auto space-x-8 flex flex-col lg:flex-row items-center justify-between">
                            <div class="w-full lg:w-1/2 flex justify-center mb-6 lg:mb-0">
                                <img src="{{ asset('storage/' . $slide->image_url) }}" alt="{{ $slide->title }}"
                                    class="rounded-lg max-h-96">
                            </div>
                            <div class="lg:w-1/2 lg:text-left py-5 md:py-0 text-center">
                                <h1 class="mb-4 text-4xl lg:text-5xl font-bold leading-tight">
                                    <span class="text-orange-500">{{ $slide->title }}</span>
                                </h1>
                                <p class="text-lg lg:text-xl mb-6">{{ $slide->description }}</p>
                                <a href="#"
                                    class="inline-block px-8 py-4 mt-4 text-white bg-orange-500 rounded-full hover:bg-orange-600 transition duration-300">
                                    Start Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Add pagination -->
            <div class="hero-swiper-pagination text-center py-4"></div>
        </div>
    </section>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            slidesPerView: 1,
            pagination: {
                el: '.hero-swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
