@php
    $menu = [
        ['label' => '집', 'icon' => 'home', 'active' => true],
        ['label' => '탐색', 'icon' => 'search'],
        ['label' => '알림', 'icon' => 'bell'],
        ['label' => '팔로우', 'icon' => 'follow'],
        ['label' => '채팅', 'icon' => 'message'],
        ['label' => '그로크', 'icon' => 'grok'],
        ['label' => '북마크', 'icon' => 'bookmark'],
        ['label' => '크리에이터 스튜디오', 'icon' => 'studio'],
        ['label' => '프리미엄', 'icon' => 'premium'],
        ['label' => '프로필', 'icon' => 'profile'],
        ['label' => '더 보기', 'icon' => 'more'],
    ];
@endphp

{{--
    사이드바는 화면 높이에 맞춰 항상 같은 자리에 고정된다 (sticky top).
    타임라인을 아무리 내려도 메뉴와 계정 영역은 그대로 보인다.
    창이 낮아 내용이 넘칠 때만 사이드바 안에서 스크롤된다.
--}}
<aside class="sticky top-0 flex h-screen w-[30%] shrink-0 flex-col overflow-y-auto px-3 py-1 xl:px-6">

    {{-- 1) X 로고 --}}
    <a href="{{ route('home') }}"
       class="mt-1 mb-1 inline-flex size-[52px] items-center justify-center self-start rounded-full transition hover:bg-white/10">
        <x-icon name="x-logo" class="size-7 text-white"/>
        <span class="sr-only">홈</span>
    </a>

    {{-- 2) 메뉴 --}}
    <nav class="flex flex-col items-start">
        @foreach ($menu as $item)
            <a href="#"
               @class([
                   'group flex items-center gap-5 rounded-full py-2 pr-6 pl-3 text-[20px] transition hover:bg-white/10',
                   'font-bold' => $item['active'] ?? false,
               ])>
                <x-icon :name="$item['icon']" class="size-[26px] text-white"/>
                <span class="hidden truncate text-white xl:inline">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- 3) 게시물 버튼 --}}
    <button type="button"
            class="mt-4 flex h-[52px] w-[52px] items-center justify-center rounded-full bg-white text-[17px] font-bold text-black transition hover:bg-white/90 xl:w-full">
        <span class="hidden xl:inline">게시물</span>
        <svg class="size-6 xl:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14"/>
        </svg>
    </button>

    {{-- 로그인한 계정 --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-auto mb-3">
        @csrf
        <button type="submit"
                class="flex w-full items-center gap-3 rounded-full p-3 text-left transition hover:bg-white/10">
            <x-avatar :name="auth()->user()->name" :src="auth()->user()->avatar" class="size-10 text-[16px]"/>
            <span class="hidden min-w-0 xl:block">
                <span class="block truncate text-[15px] font-bold text-white">{{ auth()->user()->name }}</span>
                <span class="block truncate text-[15px] text-[#71767b]">로그아웃</span>
            </span>
        </button>
    </form>
</aside>
