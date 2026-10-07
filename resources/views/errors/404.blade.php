<x-layout.app title="Page Not Found">
    <div class="container py-32 text-center">
        <h1 class="font-display text-display-xl mb-4">404</h1>
        <p class="text-body-lg text-stone-600 dark:text-stone-400 mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn-primary">Back to Home</a>
    </div>
</x-layout.app>