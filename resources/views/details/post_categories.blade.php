@extends('layouts.master')

@section('title', $page->title)

@section('content')
    @use('App\Models\Language', 'Language')
    @php
        $language_id = $page->language_id;
        $language = Language::findOrFail($language_id);
    @endphp
    <section>
        <!-- Page Content -->
        <div class="content">
            @livewire('post-category', ['language_id' => $language_id, 'category_id' => $page->id, 'page' => $page])
        </div>
    </section>

@endsection
