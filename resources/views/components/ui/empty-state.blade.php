@props([
    'title' => 'Belum ada data',
    'message' => 'Data akan muncul setelah aktivitas baru tersedia.',
    'icon' => 'inbox',
])

<div {{ $attributes->merge(['class' => 'grid place-items-center rounded-lg border border-dashed border-slate-300 px-6 py-10 text-center dark:border-white/15']) }}>
    <div class="grid h-12 w-12 place-items-center rounded-lg bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-300">
        <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
    </div>
    <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">{{ $message }}</p>
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
