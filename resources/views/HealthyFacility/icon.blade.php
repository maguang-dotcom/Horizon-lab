@php
    $paths = [
        'home' => '<path d="M4 11 12 4l8 7v8a1 1 0 0 1-1 1h-4v-6H9v6H5a1 1 0 0 1-1-1v-8Z" stroke-linejoin="round"/>',
        'plus' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8" stroke-linecap="round"/>',
        'list' => '<path d="M8 6h12M8 12h12M8 18h12M4 6h.01M4 12h.01M4 18h.01" stroke-linecap="round"/>',
        'inventory' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 10h16M10 4v16" />',
        'people' => '<circle cx="9" cy="8" r="3"/><path d="M3 20c0-3.5 2.5-6 6-6s6 2.5 6 6" stroke-linecap="round"/><circle cx="17" cy="9" r="2.5"/><path d="M15.5 14c2.6.4 4.5 2.5 4.5 6" stroke-linecap="round"/>',
        'records' => '<path d="M6 3h9l4 4v14H6z"/><path d="M15 3v4h4M9 12h6M9 16h6" stroke-linecap="round"/>',
        'chart' => '<path d="M4 20V4M4 20h16" stroke-linecap="round"/><path d="M8 16v-4M13 16V8M18 16v-7" stroke-linecap="round"/>',
        'gear' => '<circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.2l2-1.5-2-3.4-2.3.9a7 7 0 0 0-2-1.2L14.2 3h-4.4l-.4 2.6a7 7 0 0 0-2 1.2l-2.3-.9-2 3.4 2 1.5A7 7 0 0 0 5 12c0 .4 0 .8.1 1.2l-2 1.5 2 3.4 2.3-.9c.6.5 1.3.9 2 1.2L9.8 21h4.4l.4-2.6a7 7 0 0 0 2-1.2l2.3.9 2-3.4-2-1.5c.1-.4.1-.8.1-1.2Z" stroke-linejoin="round"/>',
    ];
@endphp
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-full h-full">
    {!! $paths[$name] ?? $paths['list'] !!}
</svg>