@props(['name'])

@php
    // 라인 아이콘 모음. 모두 24x24 그리드 기준.
    $paths = [
        'x-logo' => '<path fill="currentColor" stroke="none" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/>',
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9.5V20a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1V9.5"/><path d="M9.5 21v-6h5v6"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="7"/><path d="m20.5 20.5-5-5"/>',
        'bell' => '<path d="M18 8.5a6 6 0 1 0-12 0c0 5-2 6.5-2 6.5h16s-2-1.5-2-6.5Z"/><path d="M13.7 19a2 2 0 0 1-3.4 0"/>',
        'follow' => '<circle cx="9" cy="8" r="4"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M18.5 8v6M21.5 11h-6"/>',
        'message' => '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 9.6 9.6 0 0 1-3.2-.6L3 21l1.8-5.2A8.1 8.1 0 0 1 3.6 11.5a8.4 8.4 0 0 1 8.7-8.4A8.4 8.4 0 0 1 21 11.5Z"/>',
        'bookmark' => '<path d="M6 3.5h12a1 1 0 0 1 1 1v16l-7-4.5-7 4.5v-16a1 1 0 0 1 1-1Z"/>',
        'studio' => '<rect x="2.5" y="4.5" width="19" height="15" rx="2"/><path d="m10 9.5 5 2.5-5 2.5v-5Z"/>',
        'premium' => '<path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6-5.4-2.8-5.4 2.8 1-6-4.4-4.3 6.1-.9L12 3Z"/>',
        'profile' => '<circle cx="12" cy="8.5" r="4"/><path d="M4.5 20.5a7.5 7.5 0 0 1 15 0"/>',
        'more' => '<circle cx="12" cy="12" r="9.2"/><circle cx="8" cy="12" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="12" r="1" fill="currentColor" stroke="none"/>',

        // 작성 영역 도구
        'gallery' => '<rect x="2.9" y="2.9" width="18.2" height="18.2" rx="5"/><circle cx="8.7" cy="8.9" r="1.45"/><path d="m3.6 17.8 4.6-4.6 3 3 3.4-3.4 4.8 4.8"/>',
        'gif' => '<rect x="2.9" y="4.6" width="18.2" height="14.8" rx="4.4"/><text x="12" y="14.9" text-anchor="middle" font-size="7.4" font-weight="700" font-family="system-ui, -apple-system, sans-serif" fill="currentColor" stroke="none">GIF</text>',
        'grok' => '<circle cx="12" cy="12" r="8.4"/><path d="M5.6 19.4 19.1 4.7"/>',
        'list' => '<circle cx="5.1" cy="9.4" r="1.6"/><path d="M9 9.4h9.9"/><circle cx="5.1" cy="15" r="1.6"/><path d="M9 15h6.3"/>',
        'emoji' => '<circle cx="12" cy="12" r="8.9"/><path d="M8.6 14.4a4.4 4.4 0 0 0 6.8 0"/><circle cx="9.1" cy="9.9" r=".95" fill="currentColor" stroke="none"/><circle cx="14.9" cy="9.9" r=".95" fill="currentColor" stroke="none"/>',
        'calendar' => '<path d="M20.4 12.1V7.6a2 2 0 0 0-2-2H5.6a2 2 0 0 0-2 2v10.6a2 2 0 0 0 2 2h5.2"/><path d="M7.9 3.2v4.3M16.1 3.2v4.3"/><circle cx="17.1" cy="16.6" r="4.6"/><path d="M17.1 14.3v2.4l1.6 1"/>',
        'location' => '<path d="M12 21.4c4.2-4.7 6.3-8.2 6.3-10.6a6.3 6.3 0 1 0-12.6 0c0 2.4 2.1 5.9 6.3 10.6Z"/><circle cx="12" cy="10.6" r="2.25"/>',
        'flag' => '<path d="M5.9 21.3V3.3"/><path d="M5.9 3.9h13.2v8.7H5.9"/>',

        // 게시물 액션
        'comment' => '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 9.6 9.6 0 0 1-3.2-.6L3 21l1.8-5.2A8.1 8.1 0 0 1 3.6 11.5a8.4 8.4 0 0 1 8.7-8.4A8.4 8.4 0 0 1 21 11.5Z"/>',
        'repost' => '<path d="M4 8.5A3.5 3.5 0 0 1 7.5 5H17"/><path d="m14.5 2.5 3 2.5-3 2.5"/><path d="M20 15.5a3.5 3.5 0 0 1-3.5 3.5H7"/><path d="m9.5 21.5-3-2.5 3-2.5"/>',
        'like' => '<path d="M12 20.5s-7.8-4.9-7.8-10.2A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.8 2.7c0 5.3-7.8 10.2-7.8 10.2Z"/>',
        'views' => '<path d="M4 20V10M9.3 20V4M14.7 20v-7M20 20V7"/>',
        'share' => '<path d="M12 15.5V3.5"/><path d="m8 7.5 4-4 4 4"/><path d="M5 13v6.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V13"/>',
        'close' => '<path d="M6 6 18 18M18 6 6 18"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'size-6 shrink-0']) }}
     viewBox="0 0 24 24"
     fill="none"
     stroke="currentColor"
     stroke-width="1.8"
     stroke-linecap="round"
     stroke-linejoin="round"
     aria-hidden="true">
    {!! $paths[$name] ?? '' !!}
</svg>
