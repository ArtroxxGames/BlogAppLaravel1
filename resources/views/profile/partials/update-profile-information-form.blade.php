<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-5">
            <x-avatar :user="$user" size="h-20 w-20 text-xl" />
            <div class="flex-1">
                <x-input-label for="avatar" value="Foto de perfil" />
                <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp"
                       class="mt-1 block w-full text-sm text-gray-600 file:me-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                @if ($user->avatar_path)
                    <label class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                        <input type="hidden" name="remove_avatar" value="0">
                        <input type="checkbox" name="remove_avatar" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        Quitar foto
                    </label>
                @endif
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="profession" value="Profesión" />
            <x-text-input id="profession" name="profession" type="text" class="mt-1 block w-full" :value="old('profession', $user->profession)" maxlength="60" placeholder="Ej.: Desarrollador backend" />
            <x-input-error class="mt-2" :messages="$errors->get('profession')" />
        </div>

        <div>
            <x-input-label for="bio" value="Biografía" />
            <x-textarea id="bio" name="bio" rows="3" class="mt-1 block w-full" maxlength="500" placeholder="Cuéntale a los lectores quién eres.">{{ old('bio', $user->bio) }}</x-textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        @foreach (['twitter_url' => 'Twitter / X', 'linkedin_url' => 'LinkedIn', 'github_url' => 'GitHub'] as $field => $label)
            <div>
                <x-input-label :for="$field" :value="$label" />
                <x-text-input :id="$field" :name="$field" type="url" class="mt-1 block w-full" :value="old($field, $user->$field)" placeholder="https://" />
                <x-input-error class="mt-2" :messages="$errors->get($field)" />
            </div>
        @endforeach

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
