@php
    $languages = \App\Models\Language::orderBy('id')->get();
@endphp
<style>
    .text-dark{
        color: rgb(251,191,36);
    }

</style>
<div class="fi-fo-tabs flex space-x-1 overflow-auto rounded-xl bg-gray-800 p-1">
    @foreach ($languages as $lang)
        @php 
            $hasPage = isset($data['pages'][$lang->code]);
            $active  = $hasPage && $record->language_id === $lang->id;
        @endphp

        @if ($hasPage)
            <a
                href="{{ route($routeName, ['record' => $data['pages'][$lang->code]->id]) }}"
                style="{{ $active ?'border: 1px solid rgb(9, 9, 11);':'' }}"
                class="
                    inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg transition
                    {{ $active
                        ? 'bg-gray-700 text-dark shadow-sm ring-1 ring-white/10'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                    }}
                "
            >
                {{ ucfirst($lang->code) }}
            </a>
        @else
            <span
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-gray-600 cursor-not-allowed"
            >
                {{ ucfirst($lang->code) }}
            </span>
        @endif
    @endforeach
</div>
