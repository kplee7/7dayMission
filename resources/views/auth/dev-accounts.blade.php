<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>계정을 선택하세요 / {{ config('app.name', 'Laravel') }}</title>

    {{-- Styles / Scripts --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @theme {
                --font-sans: 'Pretendard', 'Apple SD Gothic Neo', 'Noto Sans KR', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    @endif
</head>
<body class="min-h-screen bg-[#0e0e0e] text-white antialiased">
    <div class="mx-auto flex min-h-screen max-w-[560px] flex-col">

        {{-- 상단 바 --}}
        <header class="flex items-center gap-3 border-b border-[#2f3336] px-8 py-4">
            <svg class="size-6 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/>
            </svg>
            <span class="text-[17px] font-bold">{{ config('app.name', 'Laravel') }} 계정으로 로그인</span>
        </header>

        <main class="flex-1 px-8 pt-12">

            {{-- 개발용 안내 배지 --}}
            <p class="mb-6 inline-block rounded border border-[#5c4400] bg-[#2b2000] px-2.5 py-1 text-[12px] font-medium text-[#ffcc4d]">
                로컬 개발용 임시 계정 · 비밀번호 없이 로그인됩니다
            </p>

            <h1 class="text-[32px] leading-tight font-normal">계정을 선택하세요.</h1>

            <p class="mt-4 text-[15px] text-white">
                <span class="text-[#8ab4f8]">{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost' }}</span>(으)로 이동
            </p>

            {{-- 계정 목록 --}}
            <ul class="mt-10">
                @forelse ($users as $user)
                    @php
                        // 이름 기준으로 아바타 색을 고정 배정한다.
                        $palette = ['#1d9bf0', '#00ba7c', '#f91880', '#ff7a00', '#7856ff', '#ffd400'];
                        $color = $palette[crc32($user->email) % count($palette)];
                    @endphp
                    <li class="border-b border-[#2f3336]">
                        <form method="POST" action="{{ route('dev.login', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center gap-4 py-4 text-left transition hover:bg-white/5">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="" class="size-10 shrink-0 rounded-full object-cover">
                                @else
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full text-[16px] font-bold text-black"
                                          style="background-color: {{ $color }}">
                                        {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="min-w-0">
                                    <span class="block truncate text-[15px] font-bold">{{ $user->name }}</span>
                                    <span class="block truncate text-[14px] text-[#a8abaf]">{{ $user->email }}</span>
                                </span>
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="border-b border-[#2f3336] py-4 text-[14px] text-[#71767b]">
                        임시 계정이 없습니다. <code class="text-white">php artisan db:seed --class=DevAccountSeeder</code> 를 실행하세요.
                    </li>
                @endforelse

                {{-- 다른 계정 사용 --}}
                <li class="border-b border-[#2f3336]">
                    <a href="{{ route('login') }}" class="flex items-center gap-4 py-4 transition hover:bg-white/5">
                        <svg class="size-10 shrink-0 p-2 text-[#a8abaf]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <circle cx="12" cy="10" r="3"/>
                            <path d="M6.5 18.5a6.5 6.5 0 0 1 11 0"/>
                        </svg>
                        <span class="text-[15px] font-bold">다른 계정 사용</span>
                    </a>
                </li>
            </ul>

            <p class="mt-10 text-[13px] leading-5 text-[#a8abaf]">
                앱을 사용하기 전에 {{ config('app.name', 'Laravel') }}의
                <a href="#" class="text-[#8ab4f8] hover:underline">개인정보처리방침</a> 및
                <a href="#" class="text-[#8ab4f8] hover:underline">서비스 약관</a>을 검토하세요.
            </p>
        </main>

        {{-- 하단 바 --}}
        <footer class="flex items-center justify-between px-8 py-6 text-[13px] text-[#a8abaf]">
            <span>한국어</span>
            <nav class="flex gap-6">
                <a href="#" class="hover:underline">도움말</a>
                <a href="#" class="hover:underline">개인정보처리방침</a>
                <a href="#" class="hover:underline">약관</a>
            </nav>
        </footer>
    </div>
</body>
</html>
