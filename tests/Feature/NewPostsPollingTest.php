<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DevAccountSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewPostsPollingTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        $this->seed(DevAccountSeeder::class);

        return User::firstOrFail();
    }

    public function test_새_게시물_엔드포인트는_로그인이_필요하다(): void
    {
        $this->getJson(route('home.posts.new'))->assertUnauthorized();
    }

    public function test_새_게시물_응답은_정해진_형태를_가진다(): void
    {
        $this->actingAs($this->user())
            ->getJson(route('home.posts.new'))
            ->assertOk()
            ->assertJsonStructure(['count', 'authors', 'html']);
    }

    public function test_게시물이_있으면_렌더된_마크업과_작성자를_함께_돌려준다(): void
    {
        $user = $this->user();

        // 확률적으로 빈 응답이 나오므로, 내용이 있는 응답이 나올 때까지 몇 번 시도한다.
        for ($i = 0; $i < 40; $i++) {
            $data = $this->actingAs($user)->getJson(route('home.posts.new'))->json();

            if ($data['count'] > 0) {
                $this->assertCount($data['count'], $data['authors']);
                $this->assertStringContainsString('<article', $data['html']);
                $this->assertStringContainsString('지금', $data['html']);

                foreach ($data['authors'] as $author) {
                    $this->assertArrayHasKey('name', $author);
                    $this->assertArrayHasKey('initial', $author);
                    $this->assertMatchesRegularExpression('/^#[0-9a-f]{6}$/i', $author['color']);
                }

                return;
            }
        }

        $this->fail('40번 시도했는데 새 게시물이 한 번도 생성되지 않았습니다.');
    }

    public function test_홈에_알림_pill과_피드_컨테이너가_있다(): void
    {
        $this->actingAs($this->user())
            ->get(route('home'))
            ->assertOk()
            ->assertSee('id="new-posts-pill"', false)
            ->assertSee('id="feed-posts"', false)
            ->assertSee('게시되었습니다');
    }
}
