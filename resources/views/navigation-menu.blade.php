<nav x-data="{ open: false, isSticky: false }" x-init="window.addEventListener('scroll', () => { isSticky = window.scrollY > 0 })"
    :class="{ 'bg-background shadow-md': isSticky, 'bg-light': !isSticky }" class="sticky  top-0 z-30 bg-light">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl">
        <div class="flex justify-between items-center max-h-28">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center py-2 shrink-0">
                    <!-- Desktop logo -->
                    <a href="{{ route('home') }}" class="hidden md:flex items-center text-accent">
                        <img src="{{ asset('storage/' . $settings->get('site_logo', 'default-logo.png')) }}"
                            alt="logo" class="max-h-20 max-w-[100%] object-contain" />
                    </a>

                    <!-- Mobile logo -->
                    <a href="{{ route('home') }}" class="flex md:hidden items-center text-accent">
                        <img src="{{ asset('storage/' . $settings->get('site_logo_small', 'default-mobile-logo.png')) }}"
                            alt="logo" class="max-h-16 max-w-[100%] object-contain" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link href="{{ route('user.dashboard') }}" :active="request()->routeIs('user.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                            {{ __('Shop') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- Search Form --}}
            <div class="flex items-center md:w-full max-w-md mx-auto relative">
                <!-- Category Dropdown (visually placed inside the search input on the left) -->
                <div class="absolute hidden inset-y-0 left-0 border-r md:flex items-center">
                    <x-category-dropdown :categories="$composerCategories" class="h-full pl-3 pr-2 py-2  rounded-l-md" />
                </div>

                <!-- Search Form -->
                <form action="/product/search?" method="GET" class="flex items-center md:w-full">
                    <!-- Search Input -->
                    <x-input type="text" name="product" placeholder="Search..."
                        class="w-full py-2 px-2 md:pl-36 md:pr-20 border border-gray-300 rounded-l-md rounded-r-none focus:ring-0 focus:border-0 focus:outline-none" />
                    <!-- Search Button (visually attached to the right of the search input) -->
                    <div class="flex items-center">
                        <button type="submit"
                            class="h-full hidden md:block bg-primary text-white border border-gray-300 border-l-0 rounded-r-md px-4 py-2">Search</button>
                        <button type="submit"
                            class="h-full block md:hidden bg-primary text-white border border-gray-300 border-l-0 rounded-r-md px-4 py-2">
                            <i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>



            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative text-xl ms-8">
                    <i class="fa-solid fa-heart"></i>
                </div>

                <div class="relative text-xl ms-8">
                    <livewire:shopping-cart />
                </div>

                @guest
                    <div class="relative text-xl ms-8">
                        <a href="{{ route('login') }}"> <i class="fa-solid fa-user"></i></a>
                    </div>
                @endguest

                @auth
                    <!-- Teams Dropdown -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="relative ms-3">
                            <x-dropdown align="right" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-foreground transition duration-150 ease-in-out bg-light border border-transparent rounded-md hover:text-dark focus:outline-none focus:bg-background active:bg-background">
                                            {{ Auth::user()->currentTeam->name }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-60">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-muted">
                                            {{ __('Manage Team') }}
                                        </div>

                                        <!-- Team Settings -->
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>

                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan

                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-muted"></div>

                                            <div class="block px-4 py-2 text-xs text-muted">
                                                {{ __('Switch Teams') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" />
                                            @endforeach
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="relative ms-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button
                                        class="flex text-sm transition border-2 border-transparent rounded-full focus:outline-none focus:border-secondary">
                                        <img class="object-cover w-8 h-8 rounded-full"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-foreground transition duration-150 ease-in-out bg-light border border-transparent rounded-md hover:text-dark focus:outline-none focus:bg-background active:bg-background">
                                            {{ Auth::user()->name }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-muted">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('user.orders') }}">
                                    {{ __('Orders') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-muted"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            {{-- <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-muted transition duration-150 ease-in-out rounded-md hover:text-dark hover:bg-background focus:outline-none focus:bg-background focus:text-dark">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div> --}}
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden overflow-auto">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($composerCategories as $category)
                <x-responsive-nav-link href="{{ route('product.search', ['categories' => $category->slug]) }}">
                    {{ $category->name }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-4 border-t border-muted">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="object-cover w-10 h-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="text-base font-medium text-foreground">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-muted">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-muted"></div>

                        <div class="block px-4 py-2 text-xs text-muted">
                            {{ __('Manage Team') }}
                        </div>

                        <!-- Team Settings -->
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                            :active="request()->routeIs('teams.show')">
                            {{ __('Team Settings') }}
                        </x-responsive-nav-link>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                {{ __('Create New Team') }}
                            </x-responsive-nav-link>
                        @endcan

                        <!-- Team Switcher -->
                        @if (Auth::user()->allTeams()->count() > 1)
                            <div class="border-t border-muted"></div>

                            <div class="block px-4 py-2 text-xs text-muted">
                                {{ __('Switch Teams') }}
                            </div>

                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        @endauth
    </div>

    <!-- Mobile Bottom Navbar -->
    <div class="fixed bottom-0 w-full bg-secondary text-light p-4 md:hidden shadow-2xl">
        <ul class="flex justify-between">
            <li>
                <a href="{{ route('home') }}" class="flex flex-col items-center">
                    <i class="fas fa-home"></i>
                    <span class="text-xs">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories') }}" class="flex flex-col items-center">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="text-xs">Category</span>
                </a>
            </li>
            <li>
                <livewire:shopping-cart :title="'Cart'" />
            </li>
            <li>
                <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center">
                    <i class="fas fa-user"></i>
                    <span class="text-xs">Account</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
