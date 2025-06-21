<div class="owl-carousel blog-slider nav-center d-flex">
    @foreach ($posts as $post)
        <!-- start blog item -->
        <div class="blog grid-blog aos col-md-4" data-aos="fade-up" style="padding: 30px">
            <a style="text-decoration-line: none" href="{{ '/' . $language->code . '/' . $post->slug }}">
                <div class="blog-image">
                    <img class="img-fluid border-irregular1 aos-init aos-animate"
                        style="height: 240px!important;width: 360px !important;" src="/public/storage/{{ $post->image }}"
                        alt="{{ $post->title ?? 'blog-img-alt' }}">

                </div>
                <div class="blog-content">
                    @if ($post->postCategories->isNotEmpty())
                        <div class="blog-slug">
                            <p class="text-muted"><i class="flaticon-pawprint-4"></i>
                            {{ $post->postCategories[0]['title'] ?? '' }}</p></div>
                    @endif
                    <h3 class="blog-title" style="font-size: 1.75rem;">{{ Str::limit($post['title'], 40) ?? '' }}</h3>
                    <p class="mb-0">{{ Str::limit($post['description'], 190) ?? '' }}</p>
                </div>
            </a>
        </div>
        <!-- end blog item -->
    @endforeach
</div>
