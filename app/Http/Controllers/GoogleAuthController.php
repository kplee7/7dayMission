<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Google 계정 선택 화면(accounts.google.com)으로 보낸다.
     */
    public function redirect(Request $request)
    {
        $request->session()->put('google_auth_popup', $request->boolean('popup'));

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Google 인증 후 돌아왔을 때 사용자를 찾거나 만들고 로그인시킨다.
     */
    public function callback(Request $request)
    {
        $isPopup = $request->session()->pull('google_auth_popup', false);

        // 동의 화면에서 취소하면 error=access_denied 로 되돌아온다.
        if ($request->filled('error')) {
            return $this->fail($isPopup, $request->input('error') === 'access_denied'
                ? 'Google 로그인을 취소했습니다.'
                : 'Google 로그인에 실패했습니다. 다시 시도해 주세요.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return $this->fail($isPopup, 'Google 로그인에 실패했습니다. 다시 시도해 주세요.');
        }

        if (! $googleUser->getEmail()) {
            return $this->fail($isPopup, 'Google 계정에서 이메일을 가져오지 못했습니다.');
        }

        $user = $this->findOrCreateUser($googleUser);

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return $isPopup
            ? response()->view('auth.google-popup-callback', ['redirect' => route('home')])
            : redirect()->intended(route('home'));
    }

    /**
     * google_id로 먼저 찾고, 없으면 같은 이메일의 기존 계정에 연결한다.
     * 그래야 이메일로 이미 가입한 사용자가 unique 제약에 걸리지 않는다.
     */
    private function findOrCreateUser(SocialiteUser $googleUser): User
    {
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        $attributes = [
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: Str::before($googleUser->getEmail(), '@'),
            'email' => $googleUser->getEmail(),
            'avatar' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
        ];

        if ($user) {
            $user->forceFill($attributes)->save();

            return $user;
        }

        // 소셜 전용 계정이라 비밀번호로는 로그인할 수 없는 임의 값을 넣어 둔다.
        return User::forceCreate([...$attributes, 'password' => Str::random(40)]);
    }

    /**
     * 팝업이면 팝업용 뷰로, 아니면 로그인 화면으로 오류를 전달한다.
     */
    private function fail(bool $isPopup, string $message)
    {
        return $isPopup
            ? response()->view('auth.google-popup-callback', ['error' => $message])
            : redirect()->route('login')->with('error', $message);
    }
}
