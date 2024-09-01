  {{-- Coupon Code --}}
  <div class="p-6 mb-8 bg-white rounded-lg border shadow-sm">
      <h2 class="mb-4 text-xl font-semibold">Apply Coupon</h2>
      <div class="flex items-center">
          <x-input type="text" wire:model.lazy="couponCode" class="w-full" placeholder="Enter coupon code" />
          <x-button type="button" wire:click="applyCoupon" class="ml-3">
              Apply
          </x-button>
      </div>
      <div>
          @if (session()->has('coupon_success'))
              <div
                  class="alert alert-success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4">
                  {{ session('coupon_success') }}
              </div>
          @endif
          @if (session()->has('coupon_error'))
              <div
                  class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4">
                  {{ session('coupon_error') }}
              </div>
          @endif
      </div>
  </div>
