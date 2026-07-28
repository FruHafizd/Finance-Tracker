<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-primary border border-transparent rounded-card font-semibold text-sm text-white hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 active:bg-primary-hover transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>