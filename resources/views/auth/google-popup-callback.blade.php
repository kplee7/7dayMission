<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>로그인 처리 중…</title>
</head>
<body style="background:#000;color:#71767b;font-family:system-ui,sans-serif;padding:24px;text-align:center">
    <p>{{ $error ?? '로그인 중입니다…' }}</p>

    <script>
        (function () {
            const payload = @json(isset($error)
                ? ['type' => 'google-auth-error', 'message' => $error]
                : ['type' => 'google-auth-success', 'redirect' => $redirect ?? '/']);

            if (window.opener && !window.opener.closed) {
                window.opener.postMessage(payload, window.location.origin);
                window.close();
            } else {
                // 팝업이 아니라 단독 창으로 열린 경우
                window.location.href = payload.type === 'google-auth-success'
                    ? payload.redirect
                    : @json(route('login'));
            }
        })();
    </script>
</body>
</html>
