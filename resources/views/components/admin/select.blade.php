@props([
'name',
'label' => '',
'value' => null,
'options' => [],
'select2' => false,
])

@php
$selected = old($name, $value);
$isMultiple = str_contains($name, '[]');
@endphp

<div class="mb-4">

    @if($label)
    <label class="block text-sm mb-1 text-zinc-300">
        {{ $label }}
    </label>
    @endif

    <select
        name="{{ $name }}"
        {{ $isMultiple ? 'multiple' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full bg-zinc-800 border border-zinc-700 rounded px-3 py-2 text-white ' . ($select2 ? 'select2' : '')
        ]) }}>

        @foreach($options as $key => $val)

        @php
        $isSelected = $isMultiple
        ? in_array($key, (array)$selected)
        : $selected == $key;
        @endphp

        <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>
            {{ $val }}
        </option>

        @endforeach

        {{ $slot }}

    </select>

</div>