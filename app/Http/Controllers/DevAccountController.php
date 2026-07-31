<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 로컬 개발용 계정 선택 화면. 실제 OAuth 자격증명 없이 로그인 흐름을 확인하기 위한 것으로,
 * local 환경 밖에서는 노출되지 않는다.
 */
class DevAccountController extends Controller
{
    public function __construct()
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new NotFoundHttpException;
        }
    }

    /**
     * 임시 계정 목록을 보여준다.
     */
    public function index()
    {
        return view('auth.dev-accounts', [
            'users' => User::orderBy('id')->get(),
        ]);
    }

    /**
     * 선택한 임시 계정으로 로그인시킨다.
     */
    public function login(Request $request, User $user)
    {
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
