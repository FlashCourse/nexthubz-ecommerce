@props([
    'title' => '',
    'subtitle' => '',
    'bgType' => 'light',
])

<div class="header text-center mb-8">
    <h2 class="text-4xl font-bold {{ $bgType === 'dark' ? 'text-white' : 'text-primary' }}">
        {{ $title }}
    </h2>
    @if (!empty($subtitle))
        <p class="mt-2 {{ $bgType === 'dark' ? 'text-gray-200' : 'text-muted' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
