@php
    $language_id = 1;
    $page = App\Models\Page::findOrFail(1);
@endphp
@extends('layouts.master')

@section('content')
    <div class="container py-4" style="min-height: 54vh">
        <h3 style="text-align: center">Konuşma Kanalları</h3>
        @if ($participants->isEmpty())
            <div class="alert alert-info">Henüz gelen veya giden bir mesajınız yok.</div>
        @else
            <ul class="list-group">
                @foreach ($participants as $participant)
                    <li class="list-group-item d-flex justify-content-center align-items-center">
                        <div style="width: fit-content; border: 1px solid black; padding: 10px 0px 10px 0px; min-width: 700px; display: flex; justify-content: center;">
                        <strong style="margin-top: 5px">{{ $participant->name }} ({{ $participant->phone }})</strong><br>
                        <a href="{{ route('message.thread', $participant->id) }}" class="btn btn-sm btn-primary" style="margin: 0 0 0 100px">
                            Sohbeti Aç
                        </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
