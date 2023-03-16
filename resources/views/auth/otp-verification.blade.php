<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('success'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{route('otp.getLogin')}}">
            @csrf
          <input type="hidden" name="user_id" value="{{$user_id}}">
            <div>
                <x-label for="otp" value="{{ __('Otp Code') }}" />
                <x-input id="otp" class="block mt-1 w-full" type="text" name="otp" :value="old('otp')" required autofocus autocomplete="mobile_no" placeholder="Enter Otp" />
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('login'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('login with Email ') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
