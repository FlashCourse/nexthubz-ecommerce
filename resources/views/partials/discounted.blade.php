<section class="bg-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-10">Limited Time Offers: Discounted Products</h2>

        <div class="swiper-container discounted-products-swiper overflow-x-hidden">
            <div class="swiper-wrapper">
                @foreach ($products as $product)
                    <div class="swiper-slide">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <!-- Navigation arrows -->
            <div class="discounted-products-swiper-button-prev"></div>
            <div class="discounted-products-swiper-button-next"></div>
        </div>
        <!-- Add pagination -->
        <div class="discounted-products-swiper-pagination text-center pt-12"></div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var discountedProductsSwiper = new Swiper('.discounted-products-swiper', {
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".discounted-products-swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".discounted-products-swiper-button-next",
                prevEl: ".discounted-products-swiper-button-prev",
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
