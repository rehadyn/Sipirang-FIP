@props(['class' => 'h-8 w-8'])

<svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <!-- Building/Room -->
    <path d="M3 21h18"></path>
    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
    <!-- Door -->
    <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6"></path>
    <!-- Calendar/Clock/Functionality hint (Clock for booking time) -->
    <circle cx="12" cy="9" r="2.5"></circle>
    <path d="M12 8v1.5l1 1"></path>
</svg>
