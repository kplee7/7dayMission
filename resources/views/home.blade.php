<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>홈 / {{ config('app.name', 'Laravel') }}</title>

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
<body class="bg-black text-white antialiased">
    {{-- 3분할 레이아웃 (3 : 4 : 3) --}}
    <div class="mx-auto flex min-h-screen w-full max-w-[1400px] items-start">
        @include('partials.home-sidebar')
        @include('partials.home-feed')
        @include('partials.home-aside')
    </div>

    <script>
        // 작성 필드가 비어 있으면 게시물 버튼을 비활성화하고, 입력 높이는 내용에 맞춰 늘린다.
        const composer = document.getElementById('composer');
        const composerSubmit = document.getElementById('composer-submit');

        composer.addEventListener('input', () => {
            composerSubmit.disabled = composer.value.trim() === '';

            composer.style.height = 'auto';
            composer.style.height = `${composer.scrollHeight}px`;
        });

        // ---- 영상 자동재생 ----
        // 화면에 절반 이상 보이면 재생하고, 벗어나면 멈춰서 불필요한 재생을 막는다.
        const videoObserver = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        entry.target.play().catch(() => {}); // 브라우저가 막으면 조용히 넘어간다
                    } else {
                        entry.target.pause();
                    }
                }
            },
            { threshold: 0.5 },
        );

        function activateVideos(root = document) {
            root.querySelectorAll('video[data-autoplay]:not([data-observed])').forEach((video) => {
                video.dataset.observed = '1';
                video.muted = true;
                videoObserver.observe(video);

                const toggle = video.parentElement.querySelector('[data-mute-toggle]');
                toggle?.addEventListener('click', () => {
                    video.muted = !video.muted;
                    toggle.querySelector('[data-muted-mark]').classList.toggle('hidden', !video.muted);
                    toggle.querySelector('[data-unmuted-mark]').classList.toggle('hidden', video.muted);
                });
            });
        }

        activateVideos();

        // ---- 새 게시물 실시간 알림 ----
        const POLL_INTERVAL = 5000;

        const pill = document.getElementById('new-posts-pill');
        const pillAvatars = document.getElementById('new-posts-avatars');
        const feed = document.getElementById('feed-posts');

        // pill은 타임라인을 이만큼 스크롤해 내려간 뒤에만 노출한다(px).
        const PILL_REVEAL_OFFSET = 120;

        const PILL_HIDDEN_CLASSES = ['pointer-events-none', 'opacity-0', '-translate-y-1'];
        const PILL_SHOWN_CLASSES = ['pointer-events-auto', 'opacity-100', 'translate-y-3'];

        // 아직 타임라인에 넣지 않고 대기 중인 게시물들
        let pendingHtml = '';
        let pendingAuthors = [];
        let scrolledPastReveal = window.scrollY > PILL_REVEAL_OFFSET;

        function renderPill() {
            // 대기 중인 게시물이 있어도, 위에 머물러 있는 동안에는 숨겨 둔다.
            if (pendingAuthors.length === 0 || !scrolledPastReveal) {
                pill.classList.remove(...PILL_SHOWN_CLASSES);
                pill.classList.add(...PILL_HIDDEN_CLASSES);
                pill.setAttribute('aria-hidden', 'true');
                return;
            }

            // 최대 3명까지 아바타를 겹쳐 보여준다.
            pillAvatars.replaceChildren(
                ...pendingAuthors.slice(0, 3).map((author) => {
                    const el = document.createElement('span');
                    el.className =
                        'relative flex size-6 items-center justify-center overflow-hidden rounded-full text-[11px] font-bold text-black ring-2 ring-[#1d9bf0]';
                    el.style.backgroundColor = author.color;
                    el.textContent = author.initial;

                    // 사진을 덮어씌우고, 실패하면 이니셜이 그대로 남는다.
                    const img = document.createElement('img');
                    img.src = author.photo;
                    img.alt = '';
                    img.className = 'absolute inset-0 size-full object-cover';
                    img.onerror = () => img.remove();
                    el.append(img);

                    return el;
                }),
            );

            pill.classList.remove(...PILL_HIDDEN_CLASSES);
            pill.classList.add(...PILL_SHOWN_CLASSES);
            pill.setAttribute('aria-hidden', 'false');
        }

        // 스크롤 위치가 노출 경계를 넘나들 때만 다시 그린다.
        window.addEventListener(
            'scroll',
            () => {
                const past = window.scrollY > PILL_REVEAL_OFFSET;
                if (past === scrolledPastReveal) return;

                scrolledPastReveal = past;
                renderPill();
            },
            { passive: true },
        );

        async function pollNewPosts() {
            try {
                const res = await fetch(@json(route('home.posts.new')), {
                    headers: { 'Accept': 'application/json' },
                });
                if (!res.ok) return;

                const data = await res.json();
                if (data.count === 0) return;

                // 최신 게시물이 위로 오도록 앞에 쌓는다.
                pendingHtml = data.html + pendingHtml;
                pendingAuthors = [...data.authors, ...pendingAuthors];
                renderPill();
            } catch {
                // 네트워크 오류는 조용히 넘기고 다음 주기에 다시 시도한다.
            }
        }

        // pill을 누르면 대기 중인 게시물을 타임라인 맨 위에 붙이고 최상단으로 이동한다.
        pill.addEventListener('click', () => {
            if (!pendingHtml) return;

            feed.insertAdjacentHTML('afterbegin', pendingHtml);
            activateVideos(feed); // 새로 붙은 게시물의 영상도 자동재생 대상에 넣는다

            pendingHtml = '';
            pendingAuthors = [];
            renderPill();

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        setInterval(pollNewPosts, POLL_INTERVAL);
        pollNewPosts();
    </script>
</body>
</html>
