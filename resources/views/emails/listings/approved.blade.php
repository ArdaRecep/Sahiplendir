@component('mail::message')
# Merhaba {{ $listing->user->name }},

“{{ $listing->title }}” isimli ilanınız admin tarafından **onaylandı** ve yayına alındı.

@php
    // Dil kodunu alıyoruz
    $lang = App\Models\Language::findOrFail($listing->language_id);
    // Basit ilan URL'i
    $url = url("{$lang->code}/{$listing->listing_no}");
@endphp

@component('mail::button', ['url' => $url])
İlana Git
@endcomponent

Teşekkürler,<br>
{{ config('app.name') }}
@endcomponent
