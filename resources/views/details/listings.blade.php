
@extends('layouts.master')


@section('content')
    @use('App\Models\Listing', 'Listing')
    @php
        $language_id = $page->language_id;
        $listings = Listing::all();
    @endphp


    <div class="row row--grid">

                        @if ($listings->isEmpty())
                            <h3 class="card__title" style="margin-left: 25px;">Henüz ilan vermediniz</h3>
                        @else
                            @foreach ($listings as $listing)
                                <div class="col-12 col-md-6 col-lg-4" style="margin-bottom: 30px">

                                    <div class="card">
                                        <a href="#" class="card__link">
                                            <div class="card__cover" style="height: 300px;">
                                                {{-- Örneğin listing modelinizde image alanı varsa --}}
                                                @if (isset($listing->photos))
                                                    <img class="border-irregular1 img-fluid"
                                                        src="{{ Storage::url($listing->photos[0]) }}"
                                                        alt="{{ $listing->title }}" style="height: 275px;width: 300px;">
                                                @endif
                                            </div>

                                            <div class="card__content">
                                                <h3 class="card__title">{{ $listing->title }}</h3>
                                                <p class="card__title">
                                                    {{ Str::limit($listing->description, 80) }}
                                                </p>
                                                <p class="card__title">
                                                    {{ $listing->city }} / {{ $listing->district }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>

@endsection
