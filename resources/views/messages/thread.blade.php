@php
    $language_id = 1;
    $page = App\Models\Page::findOrFail(2);
@endphp
@extends('layouts.master')

@section('content')
    @php $me = auth()->user()->id; @endphp
    <style>
        .w:hover {
            color: #fffd70 !important;
        }

        .list-group-item.active {
            background-color: #fa7817;
            border: 1px solid black;
        }
        .list-group-item {
            border: 1px solid black;
        }
    </style>
    <div class="container py-4" style="min-height: 53.6vh">
        <h3>Sohbet: {{ $user->name }}</h3>
        <div class="row">
            {{-- Sol sütunda kanal listesi --}}
            <div class="col-md-3">
                <a href="{{ route('message.threads') }}" class="btn btn-secondary mb-3 w">
                    < </a>
                        <ul class="list-group">
                            @foreach ($participants as $participant)
                                <a href="{{ route('message.thread', $participant->id) }}" style="text-decoration-line: none;">
                                    <li class="list-group-item {{ $user->id === $participant->id ? 'active' : '' }}">
                                        {{ $participant->name }}
                                    </li>
                                </a>
                            @endforeach
                        </ul>
            </div>


            {{-- Sağ sütunda konuşma --}}
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body" id="chatBody" style="max-height:600px; overflow-y:auto;">
                        @forelse($conversation as $msg)
                            <div class="mb-2 {{ $msg->sender_id === $me ? 'text-end' : 'text-start' }}">
                                <small class="text-muted">
                                    {{ $msg->created_at->format('d.m.Y H:i') }} —
                                    <strong>{{ $msg->sender_id === $me ? 'Siz' : $msg->sender->name }}</strong>
                                </small>
                                <div style="word-break: break-all"
                                    class="d-inline-block p-2 rounded 
                                {{ $msg->sender_id === $me ? 'bg-primary text-white' : 'bg-light' }}">
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
                            <input type="text" name="body" class="form-control me-2" placeholder="Mesaj yazın…"
                                required>
                            <button type="submit" class="btn btn-primary">Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function scrollChatToBottom() {
            const chat = document.getElementById('chatBody');
            if (chat) {
                // scrollTop'u, scrollHeight ile eşitleyerek en alta iniyoruz
                chat.scrollTop = chat.scrollHeight;
            }
        }

        // Sayfa ilk yüklendiğinde
        window.addEventListener('load', scrollChatToBottom);

        // Form gönderilip sayfa yeniden yüklendiğinde de
        document.addEventListener('submit', function(e) {
            // e.target, gönderilen form ise
            if (e.target.matches('form[action="{{ route('message.send') }}"]')) {
                // biraz gecikmeli, DOM güncellendikten sonra çalışsın
                setTimeout(scrollChatToBottom, 100);
            }
        });
    </script>
@endsection
