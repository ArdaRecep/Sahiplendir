@props(['name', 'language_id'])
@use('RyanChandler\FilamentNavigation\Models\Navigation')

@php
$menu = Navigation::fromNameAndLanguage($name, $language_id);
@endphp

@foreach ($menu->items as $item)
@if($item['data'])
<li class="mt-2">
    <a href="{{ $item['data']['url'] ?? '/storage/'.$item['data']['pdf_file'] }}" @if(isset($item['data']['url'])==false) target="_blank" @endif>{{ $item['label'] }}</a>
</li>
@endif
@endforeach
