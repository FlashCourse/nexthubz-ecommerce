<!-- Shipping Address -->
<div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
    <h2 class="mb-4 text-xl font-semibold">Shipping Address</h2>
    <div class="grid grid-cols-2 gap-4">
        <!-- First Name -->
        <div class="mb-4">
            <label for="firstName" class="block text-sm font-medium text-gray-600">First Name</label>
            <input type="text" wire:model="firstName" id="firstName" name="firstName"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('firstName')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Last Name -->
        <div class="mb-4">
            <label for="lastName" class="block text-sm font-medium text-gray-600">Last Name</label>
            <input type="text" wire:model="lastName" id="lastName" name="lastName"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('lastName')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Address Line 1 -->
        <div class="mb-4 col-span-2">
            <label for="address1" class="block text-sm font-medium text-gray-600">Address Line 1</label>
            <input type="text" wire:model="address1" id="address1" name="address1"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('address1')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Address Line 2 -->
        <div class="mb-4 col-span-2">
            <label for="address2" class="block text-sm font-medium text-gray-600">Address Line 2</label>
            <input type="text" wire:model="address2" id="address2" name="address2"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('address2')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- City -->
        <div class="mb-4">
            <label for="city" class="block text-sm font-medium text-gray-600">City</label>
            <input type="text" wire:model="city" id="city" name="city"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('city')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- State -->
        <div class="mb-4">
            <label for="state" class="block text-sm font-medium text-gray-600">State</label>
            <input type="text" wire:model="state" id="state" name="state"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('state')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- ZIP Code -->
        <div class="mb-4">
            <label for="zipCode" class="block text-sm font-medium text-gray-600">ZIP Code</label>
            <input type="text" wire:model="zipCode" id="zipCode" name="zipCode"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('zipCode')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Country -->
        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-gray-600">Country</label>
            <input type="text" wire:model="country" id="country" name="country"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('country')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="mb-4 col-span-2">
            <label for="phone" class="block text-sm font-medium text-gray-600">Phone Number</label>
            <input type="text" wire:model="phone" id="phone" name="phone"
                class="w-full p-2 mt-1 border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" />
            @error('phone')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
