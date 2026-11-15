@props(['title', 'message' => null])

<div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-white/60 px-6 py-14 text-center">
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5v-9Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8"/>
        </svg>
    </span>
    <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $title }}</h3>
    @if ($message)
        <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">{{ $message }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
