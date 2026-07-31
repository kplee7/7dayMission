@props(['post'])

@php
    use App\Support\DemoContent;
@endphp

<article {{ $attributes->merge(['class' => 'border-b border-[#2f3336] px-4 py-3 transition hover:bg-white/[0.03]']) }}>
    <div class="flex gap-3">
        <x-avatar :name="$post['name']" class="size-10 text-[16px]"/>

        {{-- 프로필 이미지를 제외한 텍스트끼리 왼쪽 정렬 --}}
        <div class="min-w-0 flex-1">

            {{-- 상단: 작성자 --}}
            <div class="flex items-center gap-1 text-[15px]">
                <span class="truncate font-bold text-white hover:underline">{{ $post['name'] }}</span>
                @if ($post['verified'])
                    <x-verified/>
                @endif
                <span class="truncate text-[#71767b]">&#64;{{ $post['handle'] }}</span>
                <span class="text-[#71767b]">·</span>
                <span class="shrink-0 text-[#71767b] hover:underline">{{ $post['time'] }}</span>
            </div>

            {{-- 내용 --}}
            <p class="mt-0.5 text-[15px] leading-5 whitespace-pre-line text-white">{{ $post['text'] }}</p>

            {{-- 이미지 / 영상 --}}
            @if ($post['media'])
                <div class="relative mt-3 overflow-hidden rounded-2xl border border-[#2f3336]">
                    @if ($post['media']['type'] === 'video')
                        {{-- 화면에 들어오면 자동재생, 벗어나면 일시정지된다 (home.blade.php의 IntersectionObserver) --}}
                        <video data-autoplay
                               src="{{ $post['media']['src'] }}"
                               poster="https://picsum.photos/seed/{{ $post['media']['seed'] }}/600/340"
                               muted
                               loop
                               playsinline
                               preload="metadata"
                               class="aspect-[600/340] w-full bg-black object-cover"></video>

                        <span class="absolute bottom-2 left-2 rounded bg-black/70 px-1.5 py-0.5 text-[12px] font-medium text-white">
                            {{ $post['media']['duration'] }}
                        </span>

                        {{-- 음소거 토글 --}}
                        <button type="button"
                                data-mute-toggle
                                class="absolute right-2 bottom-2 rounded-full bg-black/70 p-1.5 text-white transition hover:bg-black/90">
                            <span class="sr-only">소리 켜기 / 끄기</span>
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M11 5 6.5 9H3v6h3.5L11 19V5Z"/>
                                <path data-muted-mark d="m16 9.5 4.5 5M20.5 9.5l-4.5 5"/>
                                <path data-unmuted-mark class="hidden" d="M15.5 8.8a4.5 4.5 0 0 1 0 6.4M18.4 6.4a8.5 8.5 0 0 1 0 11.2"/>
                            </svg>
                        </button>
                    @else
                        <img src="https://picsum.photos/seed/{{ $post['media']['seed'] }}/600/340"
                             alt=""
                             loading="lazy"
                             class="aspect-[600/340] w-full object-cover">
                    @endif
                </div>
            @endif

            {{-- 액션 --}}
            <div class="mt-3 flex items-center justify-between text-[#71767b]">
                @foreach ([
                    ['icon' => 'comment', 'value' => $post['comments'], 'label' => '코멘트', 'hover' => 'hover:text-[#1d9bf0]'],
                    ['icon' => 'repost', 'value' => $post['reposts'], 'label' => '재게시', 'hover' => 'hover:text-[#00ba7c]'],
                    ['icon' => 'like', 'value' => $post['likes'], 'label' => '좋아요', 'hover' => 'hover:text-[#f91880]'],
                    ['icon' => 'views', 'value' => $post['views'], 'label' => '조회수', 'hover' => 'hover:text-[#1d9bf0]'],
                ] as $action)
                    <button type="button"
                            class="group flex items-center gap-1 text-[13px] transition {{ $action['hover'] }}">
                        <x-icon :name="$action['icon']" class="size-[18px]"/>
                        <span>{{ DemoContent::shortNumber($action['value']) }}</span>
                        <span class="sr-only">{{ $action['label'] }}</span>
                    </button>
                @endforeach

                <div class="flex items-center gap-1">
                    <button type="button" class="transition hover:text-[#1d9bf0]">
                        <x-icon name="bookmark" class="size-[18px]"/>
                        <span class="sr-only">북마크</span>
                    </button>
                    <button type="button" class="transition hover:text-[#1d9bf0]">
                        <x-icon name="share" class="size-[18px]"/>
                        <span class="sr-only">공유</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</article>
