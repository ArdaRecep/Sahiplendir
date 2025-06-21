@if ($page->published_at == null)
    @php abort(404); @endphp
@endif
@extends('layouts.master')

@section('title', $page->title)

@section('content')
    @use('App\Models\PostCategory', 'PostCategory')
    @use('App\Models\Language', 'Language')
    @use('App\Models\Post', 'Post')
    @php
        $language_id = $page->language_id;
        $language = Language::findOrFail($page->language_id);
        \Carbon\Carbon::setLocale($language->code);
    @endphp
<style>
    figure img{
        height: 363px;
        width: 716px;
    }
</style>
    <div class="page">
        <div class="container">
            <div class="row">
                <!-- Post Content Column -->
                <div class="col-lg-8 col-xl-9 card blog-card">
                    <div class="card-body" style="padding: 0;">
                        <h1 style="color: black;">{{ $page->title ?? '' }}</h1>
                        <!-- Post info-->
                        <div class="post-info border-irregular2 text-muted">
                            {{ $page->published_at->translatedFormat('d F Y') }}
                        </div>
                        <hr>
                        <!-- Preview Image -->
                        <img src="/public/storage/{{ $page->image }}" alt="" style="height: 500px;width: 878px;">
                        @if (isset($page->postCategories[0]))
                            <div style="padding-top: 10px!important;">
                                <a style="text-decoration-line: none;"
                                    href="/{{ $language->code }}/{{ $page->postCategories[0]['slug'] }}">
                                    <i class="flaticon-pawprint-1"></i> {{ $page->postCategories[0]['title'] ?? '' }}
                                </a>
                            </div>
                        @endif
                        <hr>
                        <!-- Post Content -->
                        {!! $page->data["content"] ?? '' !!}
                    </div>
                    <!--/Card-body -->
                </div>
                <!-- /col-lg -->
                <!-- Sidebar Widgets Column -->
                @livewire('blog-detail', ['language_id' => $language_id, 'post_id' => $page->id])
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </div>
@endsection
