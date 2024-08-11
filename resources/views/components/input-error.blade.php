@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-green-600']) }}>{{ $message }}</p>
@enderror
