<section class="bg-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-10">Fresh In: Just Arrived!</h2>

        <div class="swiper-container new-arrivals-swiper overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach ($products as $product)
                    <div class="swiper-slide">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <!-- Navigation arrows -->
            <div class="new-arrivals-swiper-button-prev"></div>
            <div class="new-arrivals-swiper-button-next"></div>
        </div>
        <!-- Add pagination -->
        <div class="new-arrivals-swiper-pagination text-center pt-12"></div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var newArrivalsSwiper = new Swiper('.new-arrivals-swiper', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".new-arrivals-swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".new-arrivals-swiper-button-next",
                prevEl: ".new-arrivals-swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 40,
                },
            },
        });
    });
</script>
