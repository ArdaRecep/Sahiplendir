@props(['name', 'language_id'])
@use('RyanChandler\FilamentNavigation\Models\Navigation')

@php
    $menu = Navigation::fromNameAndLanguage($name, $language_id);
@endphp
@foreach ($menu->items as $key => $item)
    @if (empty($item['children']))
        <li class="nav-item">
            <a class="nav-link"
                href=" 
        @if (isset($item['data'])) {{ $item['data']['url'] ?? '/storage/' . $item['data']['pdf_file'] }}
        @else 
            # @endif"
                @if (isset($item['data']['url']) == false) target="_blank" @endif>{{ $item['label'] }}
            </a>
        </li>
    @else
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="{{ $key }}" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"
                href="
                @if (isset($item['data'])) {{ $item['data']['url'] ?? '/storage/' . $item['data']['pdf_file'] }} 
                @else # @endif"
                @if (isset($item['data']['url']) == false) target="_blank" @endif>
                {{ $item['label'] }}
            </a>

            <div class="dropdown-menu" aria-labelledby="{{ $key }}">
                @foreach ($item['children'] as $childrenItem)
                        <a class="dropdown-item" href="
                            @if (isset($childrenItem['data'])) {{ $childrenItem['data']['url'] ?? '/storage/' . $childrenItem['data']['pdf_file'] }} 
                            @else # @endif"
                            @if (isset($item['data']['url']) == false) target="_blank" @endif>
                                {{ $childrenItem['label'] }}
                        </a>
                @endforeach
            </div>
        </li>
    @endif
@endforeach
