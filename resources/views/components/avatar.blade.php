@props(['name', 'src' => null])

@php
    use App\Support\DemoContent;
    use Illuminate\Support\Str;

    $photo = $src ?: DemoContent::avatarUrl($name);
@endphp

{{--
    색상 원 + 이니셜을 먼저 깔고 그 위에 사진을 덮는다.
    사진 로드가 실패하면 <img>를 제거해 이니셜이 그대로 남는다.
--}}
<span {{ $attributes->merge(['class' => 'relative flex shrink-0 items-center justify-center overflow-hidden rounded-full font-bold text-black']) }}
      style="background-color: {{ DemoContent::avatarColor($name) }}">
    <span aria-hidden="true">{{ Str::upper(Str::substr($name, 0, 1)) }}</span>
    <img src="{{ $photo }}"
         alt=""
         loading="lazy"
         onerror="this.remove()"
         class="absolute inset-0 size-full object-cover">
</span>
