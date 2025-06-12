<div class="container">
    <style>
        .badge {
            background-color: #fa7817;
            text-decoration-line: none;
        }

        .badge:hover {
            color: black !important;
        }

        .src:hover {
            color: #fffd70 !important;
        }
    </style>

<div class="container">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-lg-8 col-xl-9">
                @forelse ($posts as $post)
                    <!-- Blog Post -->
                    <div class="card blog-card">
                        <div class="post-info border-irregular2 text-muted">
                            {{ $post->published_at->translatedFormat('d F Y') }}
                        </div>
                        <a href="{{ '/' . $language->code . '/' . $post->slug }}">
                            <!-- image -->
                            <img class="card-img-top img-fluid" src="/storage/{{ $post->image }}" alt="">
                        </a>
                        @if (isset($post->postCategories[0]))
                            <div style="padding-top: 10px!important;">
                                <a style="text-decoration-line: none;"
                                    href="/{{ $language->code }}/{{ $post->postCategories[0]['slug'] }}">
                                    <i class="flaticon-pawprint-1"></i> {{ $post->postCategories[0]['title'] ?? '' }}
                                </a>
                            </div>
                        @endif
                        <div class="card-body">
                            <a href="{{ '/' . $language->code . '/' . $post->slug }}">
                                <h3 class="card-title">{{ $post->title ?? '' }}</h3>
                            </a>
                            <!-- excerpt -->
                            <p class="card-text mt-3">{{ $post->description ?? '' }}</p>
                            <a href="{{ '/' . $language->code . '/' . $post->slug }}" class="btn btn-primary">Devamını
                                Oku &rarr;</a>
                        </div>
                        <!--card-footer -->
                    </div>
                    <!-- End Blog Post -->
                @empty
                    <div class="alert alert-warning d-block" role="alert">
                        {{ $page['title'] }} Kategorisinde Aradığınız Kriterlere Uygun Blog Bulunamadı!
                    </div>
                @endforelse
                <!-- /card blog-card -->
            </div>
            <!-- /col-lg-8 -->
            <!-- Sidebar Widgets Column -->
            <div class="blog-sidebar bg-light-custom  h-50 border-irregular1 col-lg-4 col-xl-3">
                <!-- Search Widget -->
                <div class="card">
                    <h5 class="card-header">Ara</h5>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" wire:model.live='search' class="form-control" placeholder="Ara...">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary src" type="button"><i
                                        class="flaticon-animal-1"></i></button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card">
                    <h5 class="card-header">Kategoriler</h5>
                    <div class="card-body">
                        @foreach ($categories as $category)
                            <a href="{{ '/' . $language->code . '/' . $category->slug }}"
                                class="badge badge-pill badge-default">
                                {{ $category->title ?? '' }}
                            </a>
                        @endforeach

                    </div>
                </div>
                <!-- Side Widget -->
                <div class="card post-widget">
                    <h5 class="card-header">Öne Çıkanlar</h5>
                    @if ($postCount > 0)
                        <div class="card-body">
                            <ul class="latest-posts" style="padding: 5px;">
                                @foreach ($posts->take(3) as $related_post)
                                    <div class="post-thumb">
                                        <a href="{{ '/' . $language->code . '/' . $related_post->slug }}">
                                            <img class="img-fluid" src="/storage/{{ $related_post->image }}"
                                                alt="{{ $related_post->title ?? '' }}">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h6 style="font-weight: 500;">
                                            <a style="text-decoration-line: none;"
                                                href="{{ '/' . $language->code . '/' . $related_post->slug }}">{{ $related_post->title ?? '' }}</a>
                                        </h6>
                                    </div>
                                @endforeach
                    @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>