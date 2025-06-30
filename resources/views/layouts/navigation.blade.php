<nav x-data="{ open: false }" class="bg-[#B3EFEB] text-[#2c3e50] border-b-4 border-[#08A398] font-['Josefin_Sans']">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/mpp.png') }}" alt="MPP Logo" class="h-12" style="height: 50px;">
                </a>
                <span class="ml-3 text-xl font-bold text-[#08A398]">Brīvprātīgā uzskaite</span>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex space-x-6 text-lg">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Iespējas
                </x-nav-link>
                <x-nav-link :href="route('parskats.index')" :active="request()->routeIs('parskats.index')">
                    Pārskats
                </x-nav-link>
                @auth('admin')
                    <x-nav-link :href="route('lietotaji.index')" :active="request()->routeIs('lietotaji.index')">
                        Lietotāji
                    </x-nav-link>
                    <x-nav-link :href="route('apliecinajums.index')" :active="request()->routeIs('apliecinajums.index')">
                        Apliecinājumi
                    </x-nav-link>
                @endauth
                <x-nav-link :href="route('par-mums')" :active="request()->routeIs('par-mums')">
                    Par mums
                </x-nav-link>
            </div>

            <!-- Lietotāja izvēlne -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-[#2c3e50] hover:bg-[#ADE9A1] transition">
                            {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->vards : Auth::user()->vards }}
                            <svg class="ms-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (session('user_type') === 'admin')
                            <x-dropdown-link :href="route('admin.profile')">Profils</x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">Profils</x-dropdown-link>
                        @endif

                        @auth('web')
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Izrakstīties
                                </x-dropdown-link>
                            </form>
                        @endauth

                        @auth('admin')
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('admin.logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Izrakstīties
                                </x-dropdown-link>
                            </form>
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger ikona -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-[#08A398] hover:bg-[#ADE9A1] focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Iespējas</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('parskats.index')" :active="request()->routeIs('parskats.index')">Pārskats</x-responsive-nav-link>
            @auth('admin')
                <x-responsive-nav-link :href="route('lietotaji.index')" :active="request()->routeIs('lietotaji.index')">Lietotāji</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('apliecinajums.index')" :active="request()->routeIs('apliecinajums.index')">Apliecinājumi</x-responsive-nav-link>
            @endauth
            <x-responsive-nav-link :href="route('par-mums')" :active="request()->routeIs('par-mums')">Par mums</x-responsive-nav-link>
            @if (session('user_type') === 'admin')
                <x-dropdown-link :href="route('admin.profile')">Profils</x-dropdown-link>
            @else
                <x-dropdown-link :href="route('profile.edit')">Profils</x-dropdown-link>
            @endif
        </div>
    </div>
</nav>
