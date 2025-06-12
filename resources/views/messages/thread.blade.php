@php
    $language_id=1;
    $page = App\Models\Page::findOrFail(2);
@endphp
@extends('layouts.master')

@section('content')
@php $me = auth()->user()->id; @endphp

<div class="container py-4">
    <h3>Sohbet: {{ $user->name }}</h3>
    <div class="row">
        {{-- Sol sütunda kanal listesi --}}
        <div class="col-md-4">
            <a href="{{ route('message.threads') }}" class="btn btn-secondary mb-3">
                ← Kanallara Dön
            </a>
            <ul class="list-group">
                @foreach(App\Models\SimpleMessage::where('recipient_id', $me)->pluck('sender_id')->unique() as $senderId)
                    @php $p = App\Models\SiteUser::find($senderId); @endphp
                    <li class="list-group-item {{ $user->id === $senderId ? 'active' : '' }}">
                        <a href="{{ route('message.thread', $senderId) }}">
                            {{ $p->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Sağ sütunda konuşma --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" style="max-height:400px; overflow-y:auto;">
                    @forelse($conversation as $msg)
                        <div class="mb-2 {{ $msg->sender_id=== $me ? 'text-end' : 'text-start' }}">
                            <small class="text-muted">
                                {{ $msg->created_at->format('d.m.Y H:i') }} —
                                <strong>{{ $msg->sender_id=== $me ? 'Siz' : $msg->sender->name }}</strong>
                            </small>
                            <div class="d-inline-block p-2 rounded 
                                {{ $msg->sender_id=== $me ? 'bg-primary text-white' : 'bg-light' }}">
                                {{ $msg->body }}
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Henüz mesaj yok.</p>
                    @endforelse
                </div>
                {{-- Cevap formu --}}
                <div class="card-footer">
                    <form action="{{ route('message.send') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $user->id }}">
                        <input type="text" name="body" class="form-control me-2" placeholder="Mesaj yazın…" required>
                        <button type="submit" class="btn btn-primary">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
