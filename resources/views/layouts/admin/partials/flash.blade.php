@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/40" role="status">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="m5 13 4 4L19 7"/>
        </svg>
        <p class="text-body-sm text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-950/40" role="alert">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
        </svg>
        <p class="text-body-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-950/40" role="alert">
        <p class="text-body-sm font-medium text-red-800 dark:text-red-300 mb-1">Please fix the following:</p>
        <ul class="list-disc space-y-0.5 pl-5">
            @foreach($errors->all() as $error)
                <li class="text-body-sm text-red-700 dark:text-red-400">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
