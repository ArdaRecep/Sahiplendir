@if (isset($section->data['type']))
    @if ($section->data['type'] == 'home')
        <section id="services" class="cat-bg3" style="padding-top: 0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 aos" data-aos="fade-up">

                        @if (isset($section->data['title']))
                            <div class="section-heading text-center">
                                <h5>{{ $section->data['top_title'] ?? '' }}</h5>
                                <h2 class="">{{ $section->data['title'] }}</h2>
                            </div>
                        @endif
                    </div>
                </div>
                @livewire('home-blog', ['language_id' => $language_id])
                @if ($section->data['button_link'])
                    <div class="view-all text-center aos" data-aos="fade-up">
                        <a href="{{ $section->data['button_link'] ?? '' }}"
                            class="btn btn-primary">{{ $section->data['button_text'] ?? '' }}</a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($section->data['type'] == 'all')
        <section class="doctor-content content" style="padding-top: 30px">
            <!-- Page Content -->
            @if (isset($section->data['top_title']))
                <div class="section-heading text-center">
                    <h2>{{ $section->data['top_title'] }}</h2>
                </div>
            @endif
            <div class="content">
                @livewire('blog', ['language_id' => $language_id])
            </div>
        </section>
        {{-- <!-- Front view for Blog -->
<section class=" right-side-bar">
    <div class="container">
        @livewire('blog', ['language_id' => $language_id])
    </div>
</section> --}}
    @endif
@endif
