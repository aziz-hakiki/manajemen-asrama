@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-indigo-50 text-indigo-700 font-semibold text-sm border-l-4 border-indigo-600 transition-colors'
            : 'group flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium text-sm border-l-4 border-transparent transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
