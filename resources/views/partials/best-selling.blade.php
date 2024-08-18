<section class="bg-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-10">Best Selling</h2>

        <div class="swiper-container best-selling-swiper overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach ($products as $product)
                    <div class="swiper-slide">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <!-- If you want navigation arrows -->
            <div class="best-selling-swiper-button-prev"></div>
            <div class="best-selling-swiper-button-next"></div>
        </div>
        <!-- Add pagination -->
        <div class="best-selling-swiper-pagination text-center pt-12"></div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var bestSellingSwiper = new Swiper('.best-selling-swiper', {
            loop: true,
            slidesPerView: 2,
            spaceBetween: 10,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".best-selling-swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".best-selling-swiper-button-next",
                prevEl: ".best-selling-swiper-button-prev",
            },
            // Responsive breakpoints
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
            // Add other configurations as needed
        });
    });
</script>
