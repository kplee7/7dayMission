<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    /**
     * Socialite가 돌려줄 구글 사용자를 흉내낸다.
     */
    private function fakeGoogleUser(string $id, string $email, string $name = '구글 사용자'): void
    {
        $socialiteUser = (new SocialiteUser)->map([
            'id' => $id,
            'name' => $name,
            'nickname' => null,
            'email' => $email,
            'avatar' => 'https://lh3.googleusercontent.com/test.jpg',
        ]);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_redirect는_구글_인증_주소로_보낸다(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirectContains('accounts.google.com');
        $response->assertRedirectContains('prompt=select_account');
        $response->assertRedirectContains('client_id=test-client-id');
    }

    public function test_처음_로그인하면_계정이_생성되고_홈으로_이동한다(): void
    {
        $this->fakeGoogleUser('google-1', 'newbie@gmail.com', '신규 사용자');

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('home'));

        $user = User::where('email', 'newbie@gmail.com')->firstOrFail();

        $this->assertSame('google-1', $user->google_id);
        $this->assertSame('신규 사용자', $user->name);
        $this->assertNotNull($user->avatar);
        $this->assertAuthenticatedAs($user);
    }

    public function test_같은_이메일의_기존_계정이_있으면_새로_만들지_않고_연결한다(): void
    {
        $existing = User::create([
            'name' => '기존 사용자',
            'email' => 'kippeum@gmail.com',
            'password' => 'secret',
        ]);

        $this->fakeGoogleUser('google-2', 'kippeum@gmail.com', 'kippeum');

        $this->get(route('auth.google.callback'))->assertRedirect(route('home'));

        $this->assertSame(1, User::where('email', 'kippeum@gmail.com')->count());

        $existing->refresh();
        $this->assertSame('google-2', $existing->google_id);
        $this->assertAuthenticatedAs($existing);
    }

    public function test_이미_연결된_계정으로_다시_로그인해도_중복_생성되지_않는다(): void
    {
        $this->fakeGoogleUser('google-3', 'repeat@gmail.com');

        $this->get(route('auth.google.callback'));
        $this->post(route('logout'));
        $this->get(route('auth.google.callback'));

        $this->assertSame(1, User::count());
    }

    public function test_동의_화면에서_취소하면_로그인_화면으로_돌아간다(): void
    {
        $this->get(route('auth.google.callback', ['error' => 'access_denied']))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error', 'Google 로그인을 취소했습니다.');

        $this->assertGuest();
    }

    public function test_구글_인증에_실패하면_로그인_화면으로_돌아간다(): void
    {
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andThrow(new \RuntimeException('invalid state'));
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_자격증명이_있으면_로그인_화면이_실제_o_auth로_연결된다(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('auth.google.redirect'), false);
    }
}
