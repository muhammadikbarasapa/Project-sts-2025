<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fa-solid fa-house mr-1"></i> <!-- Icon Dashboard -->
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Profile & Logout (Tanpa Dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:space-x-6">
                <!-- Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center text-gray-700 hover:text-gray-900">
                    <i class="fa-solid fa-user-circle mr-1"></i> <!-- Icon Profile -->
                    {{ Auth::user()->name }}
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-gray-700 hover:text-gray-900">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> 
                        {{ __('Log Out') }}
                    </button>
                </form>
                
            </div>

            <!-- Hamburger Menu -->
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fa-solid fa-house mr-1"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Profile & Logout -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fa-solid fa-user-circle mr-1"></i> {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
