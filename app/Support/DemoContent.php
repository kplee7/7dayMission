<?php

namespace App\Support;

/**
 * 홈 타임라인에 채워 넣을 데모용 랜덤 콘텐츠.
 * 실제 게시물 저장소가 붙기 전까지 화면을 채우는 용도다.
 */
class DemoContent
{
    private const AUTHORS = [
        ['name' => '이기쁨', 'handle' => 'kippeum7', 'verified' => true],
        ['name' => '개발자 노트', 'handle' => 'devnote2024', 'verified' => false],
        ['name' => 'Laravel Korea', 'handle' => 'laravelkr01', 'verified' => true],
        ['name' => '박서준', 'handle' => 'seojun_p88', 'verified' => false],
        ['name' => '디자인 인사이트', 'handle' => 'design_in23', 'verified' => true],
        ['name' => 'Nova Studio', 'handle' => 'novastudio9', 'verified' => true],
        ['name' => '김하늘', 'handle' => 'haneul_k3', 'verified' => false],
        ['name' => '테크 브리핑', 'handle' => 'techbrief55', 'verified' => true],
        ['name' => 'minji', 'handle' => 'minji_dev12', 'verified' => false],
        ['name' => '스타트업 위클리', 'handle' => 'startupwk7', 'verified' => false],
    ];

    private const TEXTS = [
        "새벽에 짠 코드는 아침에 다시 읽어야 한다.\n오늘도 교훈을 얻었습니다.",
        '드디어 사이드 프로젝트 첫 배포 완료했습니다. 7일 동안 매일 조금씩 쌓은 결과물이라 더 뿌듯하네요.',
        '좋은 디자인은 눈에 띄지 않는다. 사용자가 아무 생각 없이 목적을 달성했다면 그게 성공이다.',
        "요즘 팀에 도입한 규칙 하나.\n\n· PR은 400줄 이하\n· 리뷰는 24시간 안에\n· 논쟁은 코드가 아니라 문서로\n\n확실히 속도가 붙었습니다.",
        '커피 세 잔째. 리팩터링은 끝이 없고 마감은 다가온다.',
        '주말에 다녀온 전시. 공간 구성이 정말 인상적이었어요. 사진으로는 절반도 못 담았네요.',
        '캐시를 지웠더니 고쳐졌다면, 고친 게 아니라 미룬 것이다.',
        "오늘 배운 것:\n작은 함수 하나를 제대로 이름 짓는 데 쓴 10분이, 나중에 세 시간을 아껴준다.",
        '신입 때 선배가 해준 말이 아직도 기억에 남는다. "모르는 걸 모른다고 말하는 게 가장 빠른 길이다."',
        '2년 만에 노트북을 바꿨습니다. 빌드 시간이 절반으로 줄었는데 이걸 왜 이제 했나 싶네요.',
        '사용자 인터뷰 10명 끝. 우리가 확신했던 기능 중 절반은 아무도 안 쓰고 있었습니다. 겸손해지는 하루.',
        "퇴근길 한강.\n하루 종일 화면만 보다가 이런 걸 봐야 사람이 되는 것 같습니다.",
        '문서화를 미루면 미래의 내가 과거의 나를 원망하게 된다. 오늘은 README부터 썼다.',
        '스타트업 3년 차에 깨달은 것: 속도보다 방향, 방향보다 지속 가능성.',
    ];

    private const NEWS = [
        ['title' => '국내 AI 스타트업, 시리즈 B 투자 유치', 'category' => '비즈니스'],
        ['title' => '오늘 밤 페르세우스 유성우 절정', 'category' => '과학'],
        ['title' => '프로야구 순위 경쟁 막판 접전', 'category' => '스포츠'],
        ['title' => '새 프레임워크 메이저 버전 공개', 'category' => '기술'],
        ['title' => '주말 전국 대체로 흐리고 비', 'category' => '날씨'],
        ['title' => '독립 게임 신작, 출시 첫날 흥행', 'category' => '게임'],
    ];

    /** 자동재생용 짧은 샘플 영상. */
    private const VIDEOS = [
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
    ];

    private const BIOS = [
        '매일 하나씩 만들고 기록합니다. 개발 · 디자인 · 사이드 프로젝트',
        '웹에서 좋은 경험을 만드는 일을 합니다.',
        "프로덕트 디자이너\n서울에서 일하고 있어요",
        '기술과 사람 사이의 번역가. 뉴스레터 매주 화요일 발행',
    ];

    /**
     * 타임라인 게시물 목록.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function posts(int $count = 12): array
    {
        $authors = self::AUTHORS;
        $texts = self::TEXTS;
        shuffle($texts);

        return array_map(function (int $i) use ($authors, $texts) {
            $author = $authors[array_rand($authors)];

            // 게시물 절반 정도에만 미디어를 붙인다.
            $media = match (mt_rand(1, 10)) {
                1, 2, 3, 4 => ['type' => 'image', 'seed' => mt_rand(1, 900)],
                5, 6 => [
                    'type' => 'video',
                    'seed' => mt_rand(1, 900),
                    'src' => self::VIDEOS[array_rand(self::VIDEOS)],
                    'duration' => sprintf('0:%02d', mt_rand(10, 59)),
                ],
                default => null,
            };

            return [
                ...$author,
                'text' => $texts[$i % count($texts)],
                'time' => self::relativeTime(),
                'media' => $media,
                'comments' => mt_rand(0, 480),
                'reposts' => mt_rand(0, 2_400),
                'likes' => mt_rand(3, 32_000),
                'views' => mt_rand(1_200, 1_400_000),
            ];
        }, range(0, $count - 1));
    }

    /**
     * 우측 "관련 인물" 카드에 들어갈 추천 계정.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function whoToFollow(int $count = 3): array
    {
        $authors = self::AUTHORS;
        shuffle($authors);
        $bios = self::BIOS;

        return array_map(fn (int $i) => [
            ...$authors[$i],
            'bio' => $bios[$i % count($bios)],
        ], range(0, $count - 1));
    }

    /**
     * 우측 "오늘의 뉴스" 목록.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function news(int $count = 4): array
    {
        $news = self::NEWS;
        shuffle($news);

        return array_map(fn (int $i) => [
            ...$news[$i],
            'time' => mt_rand(1, 12).'시간 전',
            'posts' => mt_rand(120, 48_000),
            'avatars' => array_map(fn () => self::AUTHORS[array_rand(self::AUTHORS)]['name'], range(1, mt_rand(2, 3))),
        ], range(0, $count - 1));
    }

    /**
     * 3,456 / 1.2만 형태로 축약한다.
     */
    public static function shortNumber(int $value): string
    {
        if ($value >= 10_000) {
            return rtrim(rtrim(number_format($value / 10_000, 1), '0'), '.').'만';
        }

        return $value > 0 ? number_format($value) : '';
    }

    /**
     * 이름을 안정적인 프로필 사진으로 매핑한다.
     * 이미지 로드가 실패하면 아바타 색 + 이니셜이 그대로 보인다.
     */
    public static function avatarUrl(string $key): string
    {
        return 'https://i.pravatar.cc/160?img='.(crc32($key) % 70 + 1);
    }

    /**
     * 이름을 안정적인 아바타 색으로 매핑한다.
     */
    public static function avatarColor(string $key): string
    {
        $palette = ['#1d9bf0', '#00ba7c', '#f91880', '#ff7a00', '#7856ff', '#ffd400', '#00b8d4'];

        return $palette[crc32($key) % count($palette)];
    }

    private static function relativeTime(): string
    {
        return match (mt_rand(1, 3)) {
            1 => mt_rand(1, 59).'분',
            2 => mt_rand(1, 23).'시간',
            default => mt_rand(1, 6).'일',
        };
    }
}
