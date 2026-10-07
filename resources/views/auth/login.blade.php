<x-guest-layout>
    <div class="mb-8">
        <p class="text-overline text-accent-600 mb-3">Admin</p>
        <h1 class="font-display text-display-sm text-stone-900 dark:text-stone-50">Welcome back</h1>
        <p class="mt-2 text-body-sm text-stone-500 dark:text-stone-400">
            Sign in with your account to continue to the dashboard.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <div class="flex items-baseline justify-between gap-4">
                <x-input-label for="password" :value="__('Password')" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="inline-block rounded py-2 text-caption font-medium text-accent-600 transition-colors hover:text-accent-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2 dark:text-accent-400 dark:hover:text-accent-300">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="relative">
                {{--
                    Deliberately a raw <input> rather than <x-text-input>: inside a
                    Blade component a leading ":" is evaluated as PHP at render time,
                    so an Alpine expression such as `show ? 'text' : 'password'` would
                    fail to compile. As plain markup it reaches Alpine untouched,
                    while the static type keeps the value masked pre-hydration.
                --}}
                <input
                    id="password"
                    name="password"
                    type="password"
                    :type="show ? 'text' : 'password'"
                    required
                    autocomplete="current-password"
                    class="input block w-full pr-16"
                >

                <button
                    type="button"
                    @click="show = !show"
                    x-text="show ? '{{ __('Hide') }}' : '{{ __('Show') }}'"
                    :aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                    aria-controls="password"
                    :aria-expanded="show ? 'true' : 'false'"
                    class="absolute inset-y-0 right-0 my-auto mx-1 inline-flex h-9 items-center rounded px-2.5 text-caption font-medium text-stone-500 transition-colors hover:bg-stone-100 hover:text-stone-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-stone-100"
                >
                    Show
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-4 pt-1">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5 py-1 text-body-sm text-stone-600 dark:text-stone-400">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-stone-300 text-accent-600 shadow-sm focus:ring-accent-500 dark:border-stone-700 dark:bg-stone-950 dark:focus:ring-accent-500"
                >
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Submit -->
        <x-primary-button class="mt-2 w-full justify-center py-3.5 text-body font-medium">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
