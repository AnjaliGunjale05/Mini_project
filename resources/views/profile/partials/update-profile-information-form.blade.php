<section class="max-w-4xl mx-auto bg-white shadow-md rounded-xl p-8">

    <!-- Header -->
    <header class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ __('My Profile') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Manage your personal information and account details.") }}
        </p>
    </header>

    <!-- Email Verification -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Profile Image -->
    <div class="flex flex-col items-center mb-6">
        @if(auth()->user()->image)
            <img src="{{ asset('storage/' . auth()->user()->image) }}"
                class="w-28 h-28 rounded-full object-cover border-4 border-gray-100 shadow-sm">
        @else
            <img src="{{ asset('storage/uploads/users/dprofile.png')}}"
                class="w-28 h-28 rounded-full border-4 border-gray-100 shadow-sm">
        @endif

        <p class="mt-2 text-sm text-gray-500">Profile Picture</p>
    </div>

    <!-- Form -->
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text"
                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200"
                :value="old('name', $user->name)"
                required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Profile Image Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Change Profile Image
            </label>
            <input type="file" name="image"
                class="block w-full text-sm text-gray-600
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-pink-50 file:text-pink-600
                hover:file:bg-pink-100">
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200"
                :value="old('email', $user->email)"
                required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="text-sm mt-2 text-yellow-600">
                    {{ __('Your email is not verified.') }}

                    <button form="send-verification"
                        class="underline text-sm text-pink-600 hover:text-pink-700 ml-1">
                        {{ __('Resend verification') }}
                    </button>
                </p>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4">
            <x-primary-button class="px-6 py-2 bg-pink-600 hover:bg-pink-700 rounded-lg">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-md">
                    ✔ Profile updated
                </p>
            @endif
        </div>
    </form>
</section>