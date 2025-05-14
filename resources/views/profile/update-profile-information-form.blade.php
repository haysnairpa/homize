<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null, isUploading: false}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    isUploading = true;
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            "
                            wire:loading.attr="disabled" />

                <!-- Loading Indicator for Photo Upload -->
                <div wire:loading wire:target="photo" class="mt-2">
                    <div class="flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-indigo-600 font-medium">Memproses foto...</span>
                    </div>
                </div>

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()" wire:loading.attr="disabled" wire:target="photo">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto" wire:loading.attr="disabled" wire:target="photo,deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" class="text-lg font-semibold text-gray-700" />
            <x-input 
                id="name" 
                type="text" 
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                wire:model="state.nama" 
                required 
                autocomplete="name" 
                placeholder="Enter your name"
            />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4 mt-4">
            <x-label for="phone" value="{{ __('Phone') }}" class="text-lg font-semibold text-gray-700" />
            <div class="relative mt-1 flex">
                <div class="flex-shrink-0 inline-flex items-center px-3 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-700">
                    +62
                </div>
                <input 
                    id="phone" 
                    type="text" 
                    class="flex-1 block w-full px-4 py-3 border border-gray-300 rounded-r-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                    wire:model.defer="state.phone"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    required 
                    autocomplete="tel" 
                    placeholder="8123456789"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');"
                />
            </div>
            <div class="mt-1 text-xs text-gray-500">Format: +62 diikuti nomor tanpa awalan 0 (8-12 digit)</div>
            <x-input-error for="phone" class="mt-2" />
        </div>


        <!-- Email -->
        <div class="col-span-6 sm:col-span-4 mt-4">
            <x-label for="email" value="{{ __('Email') }}" class="text-lg font-semibold text-gray-700" />
            <x-input 
                id="email" 
                type="email" 
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                wire:model="state.email" 
                required 
                autocomplete="username" 
                placeholder="Enter your email address"
            />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-3 text-gray-600">
                    {{ __('Your email address is unverified.') }}
                    <button type="button" 
                            class="underline text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo,updateProfileInformation">
            <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Save') }}</span>
            <span wire:loading wire:target="updateProfileInformation" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            </span>
        </x-button>
    </x-slot>
</x-form-section>