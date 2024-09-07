{{-- Check if maintenance mode is enabled --}}
@if ($settings->get('maintenance_mode'))
    <div class="bg-danger text-light text-center py-2">
        {{-- Display the maintenance message or a default one if not set --}}
        {{ $settings->get('maintenance_message', 'Our website is currently undergoing maintenance. We apologize for any inconvenience.') }}
    </div>
@endif

<nav class="bg-primary px-4 text-light z-60">
    <div class="max-w-7xl mx-auto py-2">
        <ul class="flex justify-between">
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-phone-alt mr-2"></i>
                    <span class="hidden md:inline">Contact: {{ $settings->get('hotline', '(+880) 9638000380') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    <span class="hidden md:inline">Email: {{ $settings->get('email', 'contact@nexthubz.com') }}</span>
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
            <li>
                <x-theme-selector />

            </li>
        </ul>
    </div>
</nav>
