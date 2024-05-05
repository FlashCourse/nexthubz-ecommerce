<section class="bg-gray-100 py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-10">Best Selling</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php $count = 0; ?>
            <?php foreach ($products as $product): ?>
                <?php if ($count < 4): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-64 object-cover">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo $product['name']; ?></h3>
                            <p class="text-gray-600 line-clamp-2 mb-4"><?php echo $product['description']; ?></p>
                            <p class="text-gray-800 font-semibold">$<?php echo $product['price']; ?></p>
                        </div>
                    </div>
                    <?php $count++; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
