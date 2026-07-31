<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * 로컬 개발용 임시 계정. 계정 선택 화면에서 클릭 한 번으로 로그인하는 데 쓰인다.
 */
class DevAccountSeeder extends Seeder
{
    public const ACCOUNTS = [
        ['name' => 'kippeum', 'email' => 'joykippeumlee@gmail.com'],
        ['name' => '김테스트', 'email' => 'test.kim@example.com'],
        ['name' => 'Jane Doe', 'email' => 'jane.doe@example.com'],
    ];

    public function run(): void
    {
        foreach (self::ACCOUNTS as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Str::random(40),
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
