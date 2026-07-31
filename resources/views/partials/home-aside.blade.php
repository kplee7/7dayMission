@php
    use App\Support\DemoContent;
@endphp

<aside class="w-[30%] shrink-0 px-4 py-3 xl:px-6">

    {{-- 1) 관련 인물 --}}
    <section class="rounded-2xl border border-[#2f3336]">
        <h2 class="px-4 pt-3 pb-2 text-[20px] font-bold text-white">관련 인물</h2>

        @foreach ($suggestions as $person)
            <div class="px-4 py-3 transition hover:bg-white/[0.03]">
                {{-- 상단: 프로필 + 이름 + 인증마크 + 팔로우 버튼 --}}
                <div class="flex items-start gap-3">
                    <x-avatar :name="$person['name']" class="size-10 text-[16px]"/>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1">
                            <span class="truncate text-[15px] font-bold text-white hover:underline">{{ $person['name'] }}</span>
                            @if ($person['verified'])
                                <x-verified/>
                            @endif
                        </div>
                        {{-- 하단: @아이디 --}}
                        <p class="truncate text-[15px] text-[#71767b]">&#64;{{ $person['handle'] }}</p>
                    </div>

                    <button type="button"
                            class="h-8 shrink-0 rounded-full bg-white px-4 text-[14px] font-bold text-black transition hover:bg-white/90">
                        팔로우
                    </button>
                </div>

                {{-- 최하단: 소개 1~3줄 --}}
                <p class="mt-2 line-clamp-3 text-[14px] leading-5 whitespace-pre-line text-white">{{ $person['bio'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- 2) 오늘의 뉴스 --}}
    <section class="mt-4 rounded-2xl border border-[#2f3336]">
        <h2 class="px-4 pt-3 pb-2 text-[20px] font-bold text-white">오늘의 뉴스</h2>

        @foreach ($news as $item)
            <div class="px-4 py-3 transition hover:bg-white/[0.03]">
                {{-- 상단: 타이틀 + 삭제 아이콘 --}}
                <div class="flex items-start justify-between gap-3">
                    <p class="text-[15px] leading-5 font-bold text-white">{{ $item['title'] }}</p>
                    <button type="button" class="-mt-1 shrink-0 rounded-full p-1.5 text-[#71767b] transition hover:bg-[#1d9bf0]/10 hover:text-[#1d9bf0]">
                        <x-icon name="close" class="size-[16px]"/>
                        <span class="sr-only">이 뉴스 숨기기</span>
                    </button>
                </div>

                {{-- 하단: 겹친 프로필 · 시간 · 카테고리 · 게시물 수 --}}
                <div class="mt-1.5 flex flex-wrap items-center gap-x-1 text-[13px] text-[#71767b]">
                    <span class="mr-1 flex -space-x-1.5">
                        @foreach ($item['avatars'] as $avatarName)
                            <x-avatar :name="$avatarName" class="size-4 text-[9px] ring-1 ring-black"/>
                        @endforeach
                    </span>
                    <span>{{ $item['time'] }}</span>
                    <span>·</span>
                    <span>{{ $item['category'] }}</span>
                    <span>·</span>
                    <span>게시물 {{ DemoContent::shortNumber($item['posts']) }}개</span>
                </div>
            </div>
        @endforeach
    </section>
</aside>
