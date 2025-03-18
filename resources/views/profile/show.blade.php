<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-homize-blue leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Info -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex items-center gap-6">
                    <div class="shrink-0">
                        <img class="h-24 w-24 object-cover rounded-full border-4 border-homize-blue" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <div class="mt-3">
                            <button class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors text-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="bg-white shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-homize-blue">
                            {{ __('Informasi Profile') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Update informasi profile dan alamat email Anda.') }}
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('profile.update-profile-information-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="bg-white shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-homize-blue">
                            {{ __('Update Password') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="bg-white shadow-xl sm:rounded-lg mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-homize-blue">
                            {{ __('Autentikasi Dua Faktor') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Tambahkan keamanan tambahan ke akun Anda menggunakan autentikasi dua faktor.') }}
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-homize-blue">
                        {{ __('Sesi Browser') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Kelola dan logout dari sesi aktif Anda pada browser dan perangkat lain.') }}
                    </p>
                </div>
                <div class="p-6">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-red-600">
                            {{ __('Hapus Akun') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Hapus akun Anda secara permanen.') }}
                        </p>
                    </div>
                    <div class="p-6">
                        @livewire('profile.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
