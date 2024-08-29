{{-- Check if maintenance mode is enabled --}}
@if ($settings->get('maintenance_mode'))
    <div class="bg-red-500 text-white text-center py-2">
        {{-- Display the maintenance message or a default one if not set --}}
        {{ $settings->get('maintenance_message', 'Our website is currently undergoing maintenance. We apologize for any inconvenience.') }}
    </div>
@endif
<nav class="bg-primary px-4 text-white  z-10">
    <div class="max-w-7xl mx-auto py-2">
        <ul class="flex justify-between">
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-phone-alt mr-2"></i>
                    <span class="hidden md:inline">Contact: +88 09638 855555</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    <span class="hidden md:inline">Email: support@nextaven.com</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-shipping-fast mr-2"></i>
                    <span class="hidden md:inline">Free Shipping</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-money-bill mr-2"></i>
                    <span class="hidden md:inline">Money Back Guarantee</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
