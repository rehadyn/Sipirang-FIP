@props(['class' => 'h-12 w-12', 'bgClass' => 'bg-white'])

<div class="inline-flex items-center justify-center {{ $class }} {{ $bgClass }} rounded-md shadow-sm"
     style="aspect-ratio: 1;">
    <img 
        src="{{ asset('images/unm-logo.png') }}" 
        alt="UNM Logo" 
        class="w-4/5 h-4/5"
        style="object-fit: contain;"
    />
</div>
