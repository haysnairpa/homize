<x-app-layout>
    <div class="py-12 bg-homize-gray">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Info -->
            <div class="bg-white shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <div class="shrink-0 relative group">
                        <img class="h-24 w-24 object-cover rounded-full border-4 border-homize-blue" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->nama }}" />
                        
                        <!-- Photo Upload Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center rounded-full bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" 
                            onclick="document.getElementById('profile-photo-input').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        
                        <!-- Hidden File Input -->
                        <form id="profile-photo-form" action="{{ route('user-profile-photo.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input id="profile-photo-input" type="file" name="photo" class="hidden" accept="image/*" onchange="document.getElementById('profile-photo-form').submit()">
                        </form>
                    </div>
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->nama }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        <div class="mt-3">
                            <button type="button" onclick="document.getElementById('profile-form-section').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2 bg-homize-blue text-white rounded-md hover:bg-homize-blue-second transition-colors text-sm inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Flash Message for Photo Upload -->
                @if (session('status') === 'profile-photo-updated')
                    <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                        Foto profil berhasil diperbarui!
                    </div>
                @endif
                
                <!-- Error Message for Photo Upload -->
                @if ($errors->has('photo'))
                    <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
                        {{ $errors->first('photo') }}
                    </div>
                @endif
            </div>

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div id="profile-form-section" class="bg-white shadow-xl sm:rounded-lg mb-6">
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
