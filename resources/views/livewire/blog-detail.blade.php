<div class="blog-sidebar bg-light-custom  h-50 border-irregular1 col-lg-4 col-xl-3">
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
    <!-- Categories Widget -->
    <div class="card">
        <h5 class="card-header">{{ trans("theme/front.categories",[],$language->code) }}</h5>
        <div class="card-body">
            @foreach ($categories as $category)
                <a href="{{ '/' . $language->code . '/' . $category->slug }}" class="badge badge-pill badge-default">
                    {{ $category->title ?? '' }}
                </a>
            @endforeach

        </div>
    </div>
    <!-- Search Widget -->
    <div class="card">
        <h5 class="card-header">{{ trans("theme/front.search2",[],$language->code) }}</h5>
        <div class="card-body">
            <div class="input-group">
                <input type="text" wire:model.live='search' class="form-control" placeholder="{{ trans("theme/front.search",[],$language->code) }}">
                <span class="input-group-btn">
                    <button class="btn btn-secondary src" type="button"><i class="flaticon-animal-1"></i></button>
                </span>
            </div>
        </div>
    </div>
    <!-- Side Widget -->
    <div class="card post-widget">
        <h5 class="card-header">{{ trans("theme/front.popular_blogs",[],$language->code) }}</h5>
        <div class="card-body">
            <ul class="latest-posts" style="padding: 5px;">
                @forelse ($posts->take(3) as $related_post)
                    <div class="post-thumb">
                        <a href="{{ '/' . $language->code . '/' . $related_post->slug }}">
                            <img class="img-fluid" src="/storage/{{ $related_post->image }}"
                                alt="{{ $related_post->title ?? '' }}">
                        </a>
                    </div>
                    <div class="post-info" style="margin-bottom: 30px!important;">
                        <h6 style="font-weight: 500; margin-bottom:3px; margin-top:10px;">
                            <a style="text-decoration-line: none;"
                                href="{{ '/' . $language->code . '/' . $related_post->slug }}">{{ $related_post->title ?? '' }}</a>
                        </h6>
                        <p>{{ Str::limit($related_post->description, 75) ?? '' }}</p>
                    </div>
                @empty
                    <div class="alert alert-warning d-block" role="alert">
                        {{ trans("theme/front.blog_not_found",[],$language->code) }}
                    </div>
                @endforelse
            </ul>
        </div>
    </div>
