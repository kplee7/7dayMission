<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DevAccountSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevAccountLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_계정_선택_화면에_임시_계정이_표시된다(): void
    {
        $this->seed(DevAccountSeeder::class);

        $this->get(route('dev.accounts'))
            ->assertOk()
            ->assertSee('계정을 선택하세요.')
            ->assertSee('joykippeumlee@gmail.com')
            ->assertSee('다른 계정 사용');
    }

    public function test_임시_계정을_클릭하면_로그인된다(): void
    {
        $this->seed(DevAccountSeeder::class);
        $user = User::where('email', 'joykippeumlee@gmail.com')->firstOrFail();

        $this->post(route('dev.login', $user))
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_로그인하면_홈_타임라인을_볼_수_있다(): void
    {
        $this->seed(DevAccountSeeder::class);
        $user = User::where('email', 'joykippeumlee@gmail.com')->firstOrFail();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee($user->name)          // 좌측 계정 영역
            ->assertSee('당신을 위해')          // 중앙 탭바
            ->assertSee('무슨 일인가요?')       // 작성 필드
            ->assertSee('관련 인물')            // 우측 카드
            ->assertSee('오늘의 뉴스');
    }

    public function test_로그아웃하면_로그인_화면으로_돌아간다(): void
    {
        $this->seed(DevAccountSeeder::class);
        $user = User::where('email', 'joykippeumlee@gmail.com')->firstOrFail();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_비로그인_상태에서는_홈에_접근할_수_없다(): void
    {
        $this->get(route('home'))->assertRedirect(route('login'));
    }

    public function test_계정_선택_화면은_local_환경_밖에서는_노출되지_않는다(): void
    {
        app()['env'] = 'production';

        $this->get(route('dev.accounts'))->assertNotFound();
    }
}
