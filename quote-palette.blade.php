@props(['name' => 'theme', 'selected' => 'paper', 'idPrefix' => 'quote'])

@php
    $themes = [
        ['key' => 'paper', 'label' => 'ورقي', 'color' => '#fcf9f0'],
        ['key' => 'lemon', 'label' => 'ليموني', 'color' => '#e8f4a9'],
        ['key' => 'sea', 'label' => 'بحري', 'color' => '#cce9ed'],
        ['key' => 'lilac', 'label' => 'بنفسجي', 'color' => '#e2dcff'],
        ['key' => 'coral', 'label' => 'مرجاني', 'color' => '#f8d2c2'],
    ];
    $selectedTheme = old($name, $selected);
@endphp

<fieldset class="palette-picker">
    <legend>اختر بصمة لون الاقتباس</legend>
    <div class="palette-options">
        @foreach ($themes as $theme)
            <label class="palette-option" for="{{ $idPrefix }}-{{ $theme['key'] }}">
                <input id="{{ $idPrefix }}-{{ $theme['key'] }}" type="radio" name="{{ $name }}" value="{{ $theme['key'] }}" @checked($selectedTheme === $theme['key'])>
                <span class="palette-swatch" style="--swatch-color: {{ $theme['color'] }}" aria-hidden="true"></span>
                <span class="palette-name">{{ $theme['label'] }}</span>
            </label>
        @endforeach
    </div>
    <p class="palette-help">اختيارك يلوّن بطاقة الاقتباس في المكتبة، ويمكن تغييره قبل النشر.</p>
</fieldset>