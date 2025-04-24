@if($page->published_at == null) @php abort(404); @endphp @endif
@extends('layouts.master')
@section('title', $page->name)

@section('content')
@php $language_id = $page->language_id; @endphp
@foreach ($page->pagesections->sortBy('order') as $section)
    @include('sections.front.' . $section->section->slug, ['section' => $section])
@endforeach

@endsection
