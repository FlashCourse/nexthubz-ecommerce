<!-- Theme Selection Section -->
<section x-data="{
    theme: localStorage.getItem('theme') || 'default',
    dropdownOpen: false,
    themes: {
        'default': 'Default',
        'ocean': 'Ocean',
        'sunset': 'Sunset',
        'forest': 'Forest',
        'pastel': 'Pastel'
    }
}" x-init="$watch('theme', value => {
    document.documentElement.setAttribute('data-theme', value);
    localStorage.setItem('theme', value);
})" class="relative inline-block text-left">

    <!-- Dropdown Button -->
    <button @click="dropdownOpen = !dropdownOpen"
        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
        <!-- Show the selected theme or "Select Theme" if none is selected -->
        <span x-text="themes[theme] || 'Select Theme'"></span>
        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06-.02L10 10.92l3.71-3.73a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.23 8.27a.75.75 0 01-.02-1.06z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
        class="origin-top-right absolute right-0 mt-2 w-56 z-50 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1" role="none">
            <!-- Static Theme Options -->
            <a href="#" @click.prevent="theme = 'default'; dropdownOpen = false"
                :class="{ 'bg-gray-200': theme === 'default' }"
                class="flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                style="color: #333; text-decoration: none;">
                <span
                    style="background-color: #0D6EFD; width: 1rem; height: 1rem; display: inline-block; margin-right: 0.5rem; border-radius: 0.25rem;"></span>
                Default
            </a>
            <a href="#" @click.prevent="theme = 'ocean'; dropdownOpen = false"
                :class="{ 'bg-gray-200': theme === 'ocean' }"
                class="flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                style="color: #333; text-decoration: none;">
                <span
                    style="background-color: #0077B6; width: 1rem; height: 1rem; display: inline-block; margin-right: 0.5rem; border-radius: 0.25rem;"></span>
                Ocean
            </a>
            <a href="#" @click.prevent="theme = 'sunset'; dropdownOpen = false"
                :class="{ 'bg-gray-200': theme === 'sunset' }"
                class="flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                style="color: #333; text-decoration: none;">
                <span
                    style="background-color: #FF5733; width: 1rem; height: 1rem; display: inline-block; margin-right: 0.5rem; border-radius: 0.25rem;"></span>
                Sunset
            </a>
            <a href="#" @click.prevent="theme = 'forest'; dropdownOpen = false"
                :class="{ 'bg-gray-200': theme === 'forest' }"
                class="flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                style="color: #333; text-decoration: none;">
                <span
                    style="background-color: #2E7D32; width: 1rem; height: 1rem; display: inline-block; margin-right: 0.5rem; border-radius: 0.25rem;"></span>
                Forest
            </a>
            <a href="#" @click.prevent="theme = 'pastel'; dropdownOpen = false"
                :class="{ 'bg-gray-200': theme === 'pastel' }"
                class="flex items-center px-4 py-2 text-sm hover:bg-gray-100"
                style="color: #333; text-decoration: none;">
                <span
                    style="background-color: #FFB3BA; width: 1rem; height: 1rem; display: inline-block; margin-right: 0.5rem; border-radius: 0.25rem;"></span>
                Pastel
            </a>
        </div>
    </div>
</section>
