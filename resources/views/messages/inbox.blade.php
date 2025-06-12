
@extends('layouts.master')
@section('content')
<div class="container py-4">
  <h3>Mesajlarım</h3>
  @foreach($messages as $msg)
    <div class="card mb-2">
      <div class="card-body">
        <small class="text-muted">
          {{ $msg->created_at->format('d.m.Y H:i') }} —
          @if($msg->sender_id == auth()->id())
            Siz → {{ $msg->recipient->name }}
          @else
            {{ $msg->sender->name }} → Siz
          @endif
        </small>
        <p class="mt-2">{{ $msg->body }}</p>
      </div>
    </div>
  @endforeach

  {{ $messages->links() }}
</div>
@endsection
