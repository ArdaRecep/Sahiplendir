@component('mail::message')
# İlanınız İncelendi

Merhaba {{ $listing->user->name }},

Üzgünüz, "{{ $listing->title }}" isimli ilanınız kontrol edildi ancak onaylanmadı.

@if($reason ?? false)
**Red Sebebi:** {{ $reason }}
@endif

Teşekkür ederiz, iyi günler dileriz.

@endcomponent
