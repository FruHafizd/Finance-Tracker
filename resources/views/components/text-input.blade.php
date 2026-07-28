@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-border bg-white text-text placeholder-text-muted focus:border-primary focus:ring-primary rounded-card shadow-sm']) }}>