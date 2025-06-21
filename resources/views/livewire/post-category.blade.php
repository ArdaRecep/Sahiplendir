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
            <div class="col-lg-8 row">
                @forelse ($posts as $post)
                    <!-- Blog Post -->
                    <div class="card blog-card col-md-6" style="padding-top: 0; border: none;">
                        <div class="post-info border-irregular2 text-muted">
                            {{ $post->published_at->translatedFormat('d F Y') }}
                        </div>
                        <a href="{{ '/' . $language->code . '/' . $post->slug }}">
                            <!-- image -->
                            <img class="card-img-top img-fluid" src="/public/storage/{{ $post->image }}" alt="">
                        </a>
                        @if (isset($post->postCategories[0]))
                            <div style="padding-top: 10px!important;">
                                <p style="margin-bottom: 5px!important;">
                                        <a style="text-decoration-line: none;"
                                            href="/{{ $language->code }}/{{ $post->postCategories[0]['slug'] }}">
                                        <i class="flaticon-pawprint-1"></i>
                                        {{ $post->postCategories[0]['title'] ?? '' }}
                                    </a>
                                    </p>
                            </div>
                        @endif
                        <div class="card-body" style="padding: 0">
                            <a style="text-decoration-line: none"
                                href="{{ '/' . $language->code . '/' . $post->slug }}">
                                <h3 class="card-title" style="font-size: 1.75rem;">{{ Str::limit($post['title'], 40) ?? '' }}</h3>
                            </a>
                            <!-- excerpt -->
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="/{{ $language->code . '/' . $post['slug'] }}"
                                        style="text-decoration-line: none">
                                        {{ Str::limit($post['description'], 74) ?? '' }}
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-primary"
                                        href="/{{ $language->code . '/' . $post['slug'] }}">
                                        {{ trans('theme/front.read_more', [], $language->code) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--card-footer -->
                    </div>
                    <!-- End Blog Post -->
                @empty
                    <div class="alert alert-warning d-block" role="alert" style="height: fit-content">
                        @if ($language->code == 'tr')
                            {{ $page['title'] }} Kategorisinde Aradığınız Kriterlere Uygun Blog Bulunamadı!
                        @else
                            No Blogs Were Found Matching Your Search Criteria In The {{ $page['title'] }} Category.
                        @endif
                    </div>
                @endforelse
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
                <!-- /card blog-card -->
            </div>
            <!-- /col-lg-8 -->
            <!-- Sidebar Widgets Column -->
            <div class="blog-sidebar bg-light-custom  h-50 border-irregular1 col-lg-4 col-xl-3">
                <!-- Search Widget -->
                <div class="card">
                    <h5 class="card-header">{{ trans('theme/front.search2', [], $language->code) }}</h5>
                    <div class="card-body" style="padding: 20px 0 0 0;">
                        <div class="input-group">
                            <input type="text" wire:model.live='search' class="form-control"
                                placeholder="{{ trans('theme/front.search', [], $language->code) }}">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary src" type="button"><i
                                        class="flaticon-animal-1"></i></button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card">
                    <h5 class="card-header">{{ trans('theme/front.categories', [], $language->code) }}</h5>
                    <div class="card-body">
                        @foreach ($categories as $category)
                            <a href="{{ '/' . $language->code . '/' . $category->slug }}"
                                class="badge badge-pill badge-default">
                                {{ $category->title ?? '' }}
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
