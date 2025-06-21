<div id="blog-home" class="page" style="padding-top: 0">
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

        .btn-secondary:hover {
            color: #fffd70 !important;
        }
    </style>
    <div class="container">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8 row">
                @forelse ($listings as $listing)
                    @php
                        $category = App\Models\Category::findOrFail($listing->category_id)->name;
                        $subCategory = App\Models\SubCategory::findOrFail($listing->sub_category_id)->name;
                    @endphp
                    <!-- Blog Post -->
                    <div class="card blog-card col-md-6" style="padding-top: 0px!important; border: none;">
                        <div class="post-info border-irregular2 text-muted">
                            {{ $listing->created_at->translatedFormat('d F Y') }}
                        </div>
                        <a href="/{{ $language->code . '/' . $listing['listing_no'] }}">
                            <!-- image -->
                            <img class="card-img-top img-fluid" src="/public/storage/{{ $listing->photos[0] }}" alt="animal"
                                style="height: 300px; width: 340px;">
                        </a>
                        <div style="padding-top: 10px!important;">
                            <p style="margin-bottom: 5px!important;">
                                <i class="flaticon-pawprint-1"></i>
                                {{ $category ?? '' }} / {{ $subCategory ?? '' }}
                            </p>
                        </div>
                        <div class="card-body" style="padding: 0">
                            <a style="text-decoration-line: none"
                                href="/{{ $language->code . '/' . $listing['listing_no'] }}">
                                <h3 class="card-title">{{ Str::limit($listing['title'], 15) ?? '' }}</h3>
                            </a>
                            <!-- excerpt -->
                            <div class="row">
                                <div class="col-md-7">
                                    <a href="/{{ $language->code . '/' . $listing['listing_no'] }}"
                                        style="text-decoration-line: none">
                                        {{ Str::limit($listing['description'], 74) ?? '' }}
                                    </a>
                                </div>
                                <div class="col-md-5">
                                    <a class="btn btn-primary"
                                        href="/{{ $language->code . '/' . $listing['listing_no'] }}">
                                        {{ trans('theme/front.see', [], $language->code) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--card-footer -->
                    </div>
                    <!-- End Blog Post -->
                @empty
                    <div class="alert alert-warning d-block" role="alert" style="height: fit-content;">
                        {{ trans('theme/front.criter', [], $language->code) }}
                    </div>
                @endforelse
                <!-- /card blog-card -->
                <div class="mt-4">
                    {{ $listings->links() }}
                </div>
            </div>

            <!-- /col-lg-8 -->
            <!-- Sidebar Widgets Column -->
            <div class="col-md-4">
                <div class="blog-sidebar bg-light-custom border-irregular1"
                    style="position: sticky!important; top: 140px;">
                    <!-- Search Widget -->
                    <div class="card mb-12">
                        <div>
                            <div class="card mb-4 p-3">
                                <h5 class="card-header mb-3">{{ trans('theme/front.filter', [], $language->code) }}</h5>

                                {{-- Arama --}}
                                <div class="mb-3">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="{{ trans('theme/front.search', [], $language->code) }}" />
                                </div>

                                {{-- Kategori & Alt Kategori --}}
                                <div class="row gy-2 mb-3">
                                    <div class="col">
                                        <select wire:model.live="category_id" class="form-control">
                                            <option value="">{{ trans('theme/front.all_animals', [], $language->code) }}</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select wire:model.live="sub_category_id" class="form-control">
                                            <option value="">{{ trans('theme/front.all_breeds', [], $language->code) }}</option>
                                            @foreach ($subCategories as $sub)
                                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Şehir --}}
                                <div class="mb-3">
                                    <select wire:model.live="city_id" class="form-control">
                                        <option value="">{{ trans('theme/front.all_cities', [], $language->code) }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Yaş: sayı + birim --}}
                                <div class="row gy-2 mb-3">
                                    <div class="col-6">
                                        <input type="number" wire:model.live="age_value" class="form-control"
                                            placeholder="{{ trans('theme/front.age', [], $language->code) }}" min="0" />
                                    </div>
                                    <div class="col-6">
                                        <select wire:model.live="age_unit" class="form-control">
                                            <option value="">{{ trans('theme/front.unit', [], $language->code) }}</option>
                                            <option value="{{ trans('theme/front.month2', [], $language->code) }}">{{ trans('theme/front.month', [], $language->code) }}</option>
                                            <option value="{{ trans('theme/front.year2', [], $language->code) }}">{{ trans('theme/front.year', [], $language->code) }}</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Diğer filtreler --}}
                                <div class="row gy-2 mb-3">
                                    <div class="col-6">
                                        <select wire:model.live="size" class="form-control">
                                            <option value="">{{ trans('theme/front.all_sizes', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.large', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.medium', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.small', [], $language->code) }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select wire:model.live="gender" class="form-control">
                                            <option value="">{{ trans('theme/front.gender', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.male', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.female', [], $language->code) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row gy-2 mb-3">
                                    <div class="col-6">
                                        <select wire:model.live="neutered" class="form-control">
                                            <option value="">{{ trans('theme/front.neutered', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.yes', [], $language->code) }}</option>
                                            <option>{{ trans('theme/front.no', [], $language->code) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-check mb-2 form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault" wire:model.live="apartment">
                                    <label class="flexSwitchCheckDefault" for="flexSwitchCheckDefault"
                                        style="margin-top: 0; color: black;">
                                        {{ trans('theme/front.apartment', [], $language->code) }}
                                    </label>
                                </div>
                                <div class="form-check mb-4 form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault2" wire:model.live="otherAnimals">
                                    <label class="flexSwitchCheckDefault" for="flexSwitchCheckDefault2"
                                        style="margin-top: 0; color: black;">
                                        {{ trans('theme/front.other', [], $language->code) }}
                                    </label>
                                </div>

                                {{-- Sıfırla butonu --}}
                                <div class="text-end">
                                    <button wire:click="resetFilters" class="btn btn-secondary">
                                        {{ trans('theme/front.reset', [], $language->code) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</div>
