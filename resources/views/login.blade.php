<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>로그인 / {{ config('app.name', 'Laravel') }}</title>

    {{-- Styles / Scripts --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- npm 빌드 전 폴백: Tailwind 런타임 --}}
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @theme {
                --font-sans: 'Pretendard', 'Apple SD Gothic Neo', 'Noto Sans KR', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    @endif
</head>
<body class="min-h-screen bg-black text-white antialiased">
    <div class="flex min-h-screen flex-col">

        {{-- 본문: 5:5 분할 --}}
        <main class="flex flex-1 flex-col-reverse lg:flex-row">

            {{-- 좌측 (50%) --}}
            <section class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2 lg:px-16">
                <div class="w-full max-w-[420px]">

                    {{-- 타이틀 --}}
                    <h1 class="text-[50px] leading-[1.1] font-bold tracking-tight">
                        지금 일어나고<br>있는 일.
                    </h1>

                    {{-- 오류 메시지 --}}
                    <p id="login-error"
                       role="alert"
                       @class([
                           'mt-6 rounded-lg border border-[#5c1a1a] bg-[#2b0f0f] px-3 py-2 text-[14px] text-[#f4212e]',
                           'hidden' => ! session('error'),
                       ])>{{ session('error') }}</p>

                    {{-- 소셜 로그인 버튼 --}}
                    <div class="mt-10 space-y-3">
                        <button type="button"
                                class="flex h-[42px] w-full items-center justify-center gap-2 rounded-full bg-white px-4 text-[15px] font-bold text-black transition hover:bg-white/90">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.03-.24c1.12.37 2.33.57 3.56.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.23.2 2.44.57 3.56a1 1 0 0 1-.25 1.03l-2.2 2.2Z"/>
                            </svg>
                            <span>전화번호 계속</span>
                        </button>

                        @php
                            // Google 자격증명이 없으면 로컬 개발용 계정 선택 화면으로 보낸다.
                            $googleReady = filled(config('services.google.client_id'));
                        @endphp
                        <a href="{{ $googleReady ? route('auth.google.redirect') : route('dev.accounts') }}"
                           id="google-login"
                           @if ($googleReady) data-popup="1" @endif
                           class="flex h-[42px] w-full items-center justify-center gap-2 rounded-full bg-white px-4 text-[15px] font-bold text-black transition hover:bg-white/90">
                            <svg class="size-[18px]" viewBox="0 0 48 48" aria-hidden="true">
                                <path fill="#4285F4" d="M45.12 24.5c0-1.56-.14-3.06-.4-4.5H24v8.51h11.84c-.51 2.75-2.06 5.08-4.39 6.64v5.52h7.11c4.16-3.83 6.56-9.47 6.56-16.17Z"/>
                                <path fill="#34A853" d="M24 46c5.94 0 10.92-1.97 14.56-5.33l-7.11-5.52c-1.97 1.32-4.49 2.1-7.45 2.1-5.73 0-10.58-3.87-12.31-9.07H4.34v5.7A21.99 21.99 0 0 0 24 46Z"/>
                                <path fill="#FBBC05" d="M11.69 28.18a13.2 13.2 0 0 1 0-8.36v-5.7H4.34a22 22 0 0 0 0 19.76l7.35-5.7Z"/>
                                <path fill="#EA4335" d="M24 9.75c3.23 0 6.13 1.11 8.41 3.29l6.31-6.31C34.91 3.18 29.93 1 24 1 15.4 1 7.96 5.93 4.34 13.12l7.35 5.7C13.42 13.62 18.27 9.75 24 9.75Z"/>
                            </svg>
                            <span>Google로 계속</span>
                        </a>

                        <button type="button"
                                class="flex h-[42px] w-full items-center justify-center gap-2 rounded-full bg-white px-4 text-[15px] font-bold text-black transition hover:bg-white/90">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.05 12.72c-.03-2.72 2.22-4.03 2.32-4.09-1.26-1.85-3.23-2.1-3.93-2.13-1.67-.17-3.27 1-4.12 1-.85 0-2.16-.98-3.55-.95-1.83.03-3.51 1.06-4.45 2.7-1.9 3.29-.48 8.17 1.36 10.84.9 1.31 1.98 2.78 3.39 2.72 1.36-.05 1.87-.88 3.52-.88s2.11.88 3.55.85c1.47-.02 2.4-1.33 3.3-2.65 1.04-1.52 1.47-2.99 1.49-3.07-.03-.01-2.86-1.1-2.88-4.34ZM14.37 4.75c.75-.91 1.25-2.17 1.11-3.43-1.08.04-2.38.72-3.15 1.62-.69.8-1.3 2.09-1.14 3.32 1.2.09 2.43-.61 3.18-1.51Z"/>
                            </svg>
                            <span>Apple로 계속</span>
                        </button>
                    </div>

                    {{-- 또는 구분선 --}}
                    <div class="my-4 flex items-center gap-2">
                        <span class="h-px flex-1 bg-[#2f3336]"></span>
                        <span class="text-[15px] text-white">또는</span>
                        <span class="h-px flex-1 bg-[#2f3336]"></span>
                    </div>

                    {{-- 이메일 입력 --}}
                    <form id="login-form" method="POST" action="{{ url('/login') }}" class="space-y-6">
                        @csrf
                        <div class="relative">
                            <input id="identifier"
                                   name="identifier"
                                   type="text"
                                   autocomplete="username"
                                   placeholder=" "
                                   class="peer h-14 w-full rounded border-2 border-[#333639] bg-black px-2 pt-5 pb-1 text-[17px] text-white outline-none transition-colors focus:border-[#1d9bf0]">
                            <label for="identifier"
                                   class="pointer-events-none absolute left-2 top-1/2 -translate-y-1/2 text-[17px] text-[#71767b] transition-all duration-150
                                          peer-focus:top-1.5 peer-focus:translate-y-0 peer-focus:text-[13px] peer-focus:text-[#1d9bf0]
                                          peer-[&:not(:placeholder-shown)]:top-1.5 peer-[&:not(:placeholder-shown)]:translate-y-0 peer-[&:not(:placeholder-shown)]:text-[13px]">
                                이메일 또는 사용자 이름
                            </label>
                        </div>

                        {{-- 계속하기 (기본 비활성화) --}}
                        <button id="submit-btn"
                                type="submit"
                                disabled
                                class="h-[42px] w-full rounded-full bg-white text-[15px] font-bold text-black transition enabled:hover:bg-white/90 disabled:cursor-not-allowed disabled:bg-[#787a7a] disabled:text-[#0f1419]">
                            계속하기
                        </button>
                    </form>

                    {{-- 약관 문구 --}}
                    <p class="mt-6 text-[13px] leading-4 text-[#71767b]">
                        계속하면 <a href="#" class="text-[#1d9bf0] hover:underline">이용 약관</a>,
                        <a href="#" class="text-[#1d9bf0] hover:underline">개인정보 처리방침</a>,
                        <a href="#" class="text-[#1d9bf0] hover:underline">쿠키 정책</a>에 동의하게 됩니다.
                    </p>
                </div>
            </section>

            {{-- 우측 (50%) : X 로고 --}}
            <section class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2">
                <svg class="w-[45%] max-w-[380px] min-w-[120px] text-white" viewBox="0 0 24 24" fill="currentColor" aria-label="X" role="img">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/>
                </svg>
            </section>
        </main>

        {{-- 최하단 푸터 --}}
        <footer class="px-6 py-4">
            <nav class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-[13px] text-[#71767b]">
                @foreach ([
                    '소개', 'Get App', 'Grok', '도움말', 'Terms', 'Privacy',
                    'Cookies', 'Careers', 'Ads & Business', '개발자', '뉴스', '접근성',
                ] as $item)
                    <a href="#" class="hover:underline">{{ $item }}</a>
                @endforeach
            </nav>
        </footer>
    </div>

    <script>
        const input = document.getElementById('identifier');
        const submitBtn = document.getElementById('submit-btn');

        input.addEventListener('input', () => {
            submitBtn.disabled = input.value.trim() === '';
        });

        // Google 계정 선택 화면을 팝업으로 띄운다. 팝업이 차단되면 일반 이동으로 폴백.
        document.getElementById('google-login').addEventListener('click', (e) => {
            if (!e.currentTarget.dataset.popup) return; // 개발용 계정 화면은 같은 탭에서 연다

            const w = 500, h = 620;
            const left = window.screenX + (window.outerWidth - w) / 2;
            const top = window.screenY + (window.outerHeight - h) / 2;

            const popup = window.open(
                e.currentTarget.href + '?popup=1',
                'google-oauth',
                `width=${w},height=${h},left=${left},top=${top}`,
            );

            if (popup) {
                e.preventDefault();
                popup.focus();
            }
        });

        // 팝업에서 인증이 끝나면 부모 창을 이동시키고, 실패했으면 사유를 보여준다.
        window.addEventListener('message', (e) => {
            if (e.origin !== window.location.origin) return;

            if (e.data?.type === 'google-auth-success') {
                window.location.href = e.data.redirect ?? '/';
            }

            if (e.data?.type === 'google-auth-error') {
                const banner = document.getElementById('login-error');
                banner.textContent = e.data.message ?? 'Google 로그인에 실패했습니다.';
                banner.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
