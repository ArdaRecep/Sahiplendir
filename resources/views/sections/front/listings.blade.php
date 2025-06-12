@if(isset($section->data["type"]))
<style>
    .see:hover {
        background: #675444 !important;
        color: white !important;
    }

    .owl-prev:hover,
    .owl-next:hover {
        scale: 1.1 !important;
    }

    .owl-next,
    .owl-prev {
        transition: 250ms ease-in-out;
    }
</style>
@if($section->data["type"]=="home")
<section id="services" class="cat-bg3" style="padding-top: 0">
    <div class="container">
        @if (isset($section->data['title']))
            <div class="section-heading text-center">
                <h2>{{ $section->data['title'] }}</h2>
            </div>
        @endif
        @livewire('listings-home',[$language_id])
        
        @if (isset($section->data['btn_text']))
            <div class="text-center mt-5">
                <a @if (isset($section->data['btn_link'])) href="{{ $section->data['btn_link'] }}" @endif
                    class="btn btn-secondary btn-lg see">{{ $section->data['btn_text'] }}</a>
            </div>
        @endif
    </div>
</section>
@else
<section id="services" class="cat-bg3" style="padding-top: 30px">
    <div class="container">
        @if (isset($section->data['title']))
            <div class="section-heading text-center">
                <h2>{{ $section->data['title'] }}</h2>
            </div>
        @endif
        @livewire('listings',[$language_id])
        
        @if (isset($section->data['btn_text']))
            <div class="text-center mt-5">
                <a @if (isset($section->data['btn_link'])) href="{{ $section->data['btn_link'] }}" @endif
                    class="btn btn-secondary btn-lg see">{{ $section->data['btn_text'] }}</a>
            </div>
        @endif
    </div>
</section>
@endif
@endif