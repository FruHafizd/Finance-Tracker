@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-slate-800 text-start text-base font-semibold text-slate-900 bg-slate-50 focus:outline-none focus:text-slate-900 focus:bg-slate-100 focus:border-slate-900 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-slate-900 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-slate-900 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
