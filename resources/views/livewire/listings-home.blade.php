<div class="bg-light-custom block-padding border-irregular1">
    <div id="owl-services" class="container owl-carousel ">
        @foreach ($listings as $listing)
            <div class="col-md-12 p-3">
                <div class="serviceBox">
                    <div class="thumbnail text-center">
                        <!-- Image -->
                        <img src="/public/storage/{{ $listing->photos[0] }}" class="border-irregular1 img-fluid" style="width: 286px; height: 317px;" alt="">
                        <!-- Name -->
                        <div class="caption-adoption">
                            <h6 class="adoption-header" style="color: black!important;">{{ Str::limit($listing['title'], 15) }}</h6>
                            <!-- List -->
                            <ul class="list-unstyled">
                                <li><strong>{{ trans("theme/front.age",[],$language->code) }}:</strong> {{ $listing->data['age'] }}</li>
                                <li><strong>{{ trans("theme/front.gender",[],$language->code) }}:</strong> {{ $listing->data['gender'] }}</li>
                                <li><strong>{{ trans("theme/front.neutered",[],$language->code) }}:</strong> {{ $listing->data['neutered'] }}</li>
                            </ul>
                            <!-- Buttons -->
                            <div class="text-center">
                                <a href="/{{ $language->code . '/' . $listing['listing_no'] }}"
                                    class="btn btn-primary">{{ trans("theme/front.see",[],$language->code) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
