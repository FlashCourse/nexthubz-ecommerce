<!-- Payment Methods -->
<div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
    <h2 class="mb-4 text-xl font-semibold">Payment Method</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Cash on Delivery -->
        <div class="flex items-center p-4 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-200 transition-colors">
            <input type="radio" wire:model="paymentMethod" id="cash" name="paymentMethod" value="cash"
                class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
            <label for="cash" class="ml-4 text-gray-600 flex items-center cursor-pointer">
                <i class="fas fa-money-bill-wave text-2xl text-orange-500 mr-2"></i>
                <span>Cash on Delivery</span>
            </label>
        </div>

        <!-- bKash -->
        <div class="flex items-center p-4 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-200 transition-colors">
            <input type="radio" wire:model="paymentMethod" id="bkash" name="paymentMethod" value="bkash"
                class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
            <label for="bkash" class="ml-4 text-gray-600 flex items-center cursor-pointer">
                <i class="fas fa-mobile-alt text-2xl text-orange-500 mr-2"></i>
                <span>bKash</span>
            </label>
        </div>

        <!-- Card Payment -->
        <div class="flex items-center p-4 bg-gray-100 rounded-md cursor-pointer hover:bg-gray-200 transition-colors">
            <input type="radio" wire:model="paymentMethod" id="card" name="paymentMethod" value="card"
                class="w-4 h-4 text-orange-500 focus:ring-orange-500" required>
            <label for="card" class="ml-4 text-gray-600 flex items-center cursor-pointer">
                <i class="fas fa-credit-card text-2xl text-orange-500 mr-2"></i>
                <span>Card Payment</span>
            </label>
        </div>
    </div>
    @error('paymentMethod')
        <span class="text-red-500 text-sm text-center mt-2 block">{{ $message }}</span>
    @enderror
</div>
