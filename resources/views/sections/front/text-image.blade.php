@if (isset($section->data['title']))
<style>
.sticky-image {
    position: sticky;
    top: 140px; /* Üstten 20px boşluk */
    /* İsteğe bağlı: görsele border, gölge, vs. ekleyebilirsiniz */
}
</style>
    <section id="about" class="dog-bg2">
        @if (isset($section->data['top_title']))
            <div class="container">
                <div class="section-heading text-center">
                    <h2>{{ $section->data['top_title'] }}</h2>
                </div>
            </div>
        @endif
        <!-- /container -->
        <div class="container block-padding pt-0 pb-0">
            <div class="row">
                <div class="col-lg-6 my-auto">
                    <h3>{{ $section->data['title'] }}</h3>
                    {!! $section->data['content'] !!}
                    @if (isset($section->data['items']))
                        <ul class="custom ps-0">
                            @foreach ($section->data['items'] as $item)
                                <li>{{ $item['item'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if (isset($section->data['btn_text']))
                        <a @if(isset($section->data['btn_link']))href="{{ $section->data['btn_link'] }}"@endif class="btn btn-primary">
                            {{ $section->data['btn_text'] }}
                        </a>
                    @endif
                    <!-- /ul -->
                </div>
                <!-- image -->
                <div class="col-lg-6">
                    {{-- Buraya .sticky-image sınıfını ekliyoruz --}}
                    <div class="sticky-image">
                        <img src="{{ url('/public/storage/'.$section->data["image"] ) }}"
                             alt="animals"
                             class="img-fluid border-irregular1"
                             data-aos="zoom-in">
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
    </section>
@endif
