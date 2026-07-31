@php
    $composerTools = [
        ['icon' => 'gallery', 'label' => '갤러리'],
        ['icon' => 'gif', 'label' => 'GIF'],
        ['icon' => 'grok', 'label' => '그로크'],
        ['icon' => 'list', 'label' => '리스트'],
        ['icon' => 'emoji', 'label' => '이모지'],
        ['icon' => 'calendar', 'label' => '일정'],
        ['icon' => 'location', 'label' => '장소'],
        ['icon' => 'flag', 'label' => '콘텐츠 공개'],
    ];
@endphp

<main class="w-[40%] shrink-0 border-x border-[#2f3336]">

    {{-- 1) 탭바 --}}
    <div class="sticky top-0 z-20 border-b border-[#2f3336] bg-black/70 backdrop-blur-md">
        <div class="flex">
            <button type="button" class="flex flex-1 justify-center py-4 transition hover:bg-white/10">
                <span class="relative flex flex-col items-center">
                    <span class="text-[15px] font-bold text-white">당신을 위해</span>
                    <span class="absolute -bottom-4 h-1 w-full rounded-full bg-[#1d9bf0]"></span>
                </span>
            </button>
            <button type="button" class="flex flex-1 justify-center py-4 transition hover:bg-white/10">
                <span class="text-[15px] text-[#71767b]">팔로잉</span>
            </button>
        </div>

        {{-- 새 게시물 알림 pill: 탭바 바로 아래에 떠 있는다 --}}
        <button id="new-posts-pill"
                type="button"
                aria-live="polite"
                class="absolute top-full left-1/2 z-30 hidden -translate-x-1/2 translate-y-3 items-center gap-2 rounded-full bg-[#1d9bf0] py-2 pr-5 pl-4 text-[15px] font-bold text-white shadow-[0_0_16px_rgba(0,0,0,0.6)] transition hover:bg-[#1a8cd8]">
            <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 20V4"/>
                <path d="m5 11 7-7 7 7"/>
            </svg>
            <span id="new-posts-avatars" class="flex -space-x-2"></span>
            <span>게시되었습니다</span>
        </button>
    </div>

    {{-- 2) 게시물 작성 --}}
    <div class="flex gap-3 border-b border-[#2f3336] px-4 py-3">
        <x-avatar :name="auth()->user()->name" :src="auth()->user()->avatar" class="size-10 text-[16px]"/>

        <div class="min-w-0 flex-1">
            <textarea id="composer"
                      rows="1"
                      placeholder="무슨 일인가요?"
                      class="w-full resize-none bg-transparent pt-2 text-[20px] text-white placeholder-[#71767b] outline-none"></textarea>

            <div class="mt-3 flex items-center justify-between gap-2">
                {{-- 하단 좌측 도구 --}}
                <div class="-ml-2 flex items-center">
                    @foreach ($composerTools as $tool)
                        <button type="button"
                                title="{{ $tool['label'] }}"
                                class="rounded-full p-2 text-[#71767b] transition hover:bg-[#1d9bf0]/10 hover:text-[#1d9bf0]">
                            <x-icon :name="$tool['icon']" class="size-[19px]"/>
                            <span class="sr-only">{{ $tool['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- 하단 우측 게시물 버튼 --}}
                <button id="composer-submit"
                        type="button"
                        disabled
                        class="h-9 shrink-0 rounded-full bg-white px-4 text-[15px] font-bold text-black transition enabled:hover:bg-white/90 disabled:cursor-not-allowed disabled:opacity-50">
                    게시물
                </button>
            </div>
        </div>
    </div>

    {{-- 3) 게시물 목록 --}}
    <div id="feed-posts">
        @foreach ($posts as $post)
            <x-post :post="$post"/>
        @endforeach
    </div>
</main>
