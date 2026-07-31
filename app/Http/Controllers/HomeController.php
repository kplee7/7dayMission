<?php

namespace App\Http\Controllers;

use App\Support\DemoContent;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * 홈 타임라인.
     */
    public function index()
    {
        return view('home', [
            'posts' => DemoContent::posts(),
            'suggestions' => DemoContent::whoToFollow(),
            'news' => DemoContent::news(),
        ]);
    }

    /**
     * 폴링으로 가져가는 "새로 게시된" 게시물.
     * 매번 쏟아지지 않도록 일정 확률로만 만들어 준다.
     */
    public function newPosts(): JsonResponse
    {
        if (random_int(1, 10) > 7) {
            return response()->json(['count' => 0, 'authors' => [], 'html' => '']);
        }

        $posts = array_map(
            fn (array $post) => [...$post, 'time' => '지금'],
            DemoContent::posts(random_int(1, 2)),
        );

        return response()->json([
            'count' => count($posts),
            'authors' => array_map(fn (array $post) => [
                'name' => $post['name'],
                'initial' => mb_strtoupper(mb_substr($post['name'], 0, 1)),
                'color' => DemoContent::avatarColor($post['name']),
                'photo' => DemoContent::avatarUrl($post['name']),
            ], $posts),
            'html' => collect($posts)
                ->map(fn (array $post) => view('components.post', ['post' => $post])->render())
                ->implode(''),
        ]);
    }
}
