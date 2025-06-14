<div class="owl-carousel blog-slider nav-center d-flex">
    @foreach ($posts as $post)
        <!-- start blog item -->
        <div class="blog grid-blog aos col-md-4" data-aos="fade-up" style="padding: 30px">
            <div class="blog-image">
                <a href="{{ '/' . $language->code . '/' . $post->slug }}"><img
                        class="img-fluid border-irregular1 aos-init aos-animate" style="height: 240px!important;width: 360px !important;"
                        src="/storage/{{ $post->image }}" alt="{{ $post->title ?? 'blog-img-alt' }}"></a>

            </div>
            <div class="blog-content">
                @if ($post->postCategories->isNotEmpty())
                    <div class="blog-slug"><i class="flaticon-pawprint-4"></i>
                        {{ $post->postCategories[0]['title'] ?? '' }}</div>
                @endif
                <h3 class="blog-title" style="font-size: 1.75rem;"><a style="text-decoration-line: none"
                        href="{{ '/' . $language->code . '/' . $post->slug }}">{{ Str::limit($post['title'], 40) ?? '' }}</a></h3>
                <p class="mb-0">{{ $post->description ?? '' }}</p>
            </div>
        </div>
        <!-- end blog item -->
    @endforeach
</div>