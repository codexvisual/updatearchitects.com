<x-layout.app title="Server Error">
    <div class="container py-32 text-center">
        <h1 class="font-display text-display-xl mb-4">500</h1>
        <p class="text-body-lg text-stone-600 dark:text-stone-400 mb-8">Something went wrong on our end. Please try again later.</p>
        <a href="{{ route('home') }}" class="btn-primary">Back to Home</a>
    </div>
</x-layout.app>