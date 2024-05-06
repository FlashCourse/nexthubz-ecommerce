<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-10">Best Selling</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php $count = 0; ?>
            <?php foreach ($products as $product): ?>
            <?php if ($count < 4): ?>
            <x-product-card :product=$product />
            <?php $count++; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
