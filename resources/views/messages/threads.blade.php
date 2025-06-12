@php
    $language_id = 1;
    $page = App\Models\Page::findOrFail(1);
@endphp
@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <h3>Konuşma Kanalları</h3>
        @if ($participants->isEmpty())
            <div class="alert alert-info">Henüz size mesaj atan kimse yok.</div>
        @else
            <ul class="list-group">
                @foreach ($participants as $participant)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $participant->name }} ({{ $participant->email }})
                        <a href="{{ route('message.thread', $participant->id) }}" class="btn btn-sm btn-primary">
                            Sohbeti Aç
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
