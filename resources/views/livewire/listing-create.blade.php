<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4" style="text-align: center">{{ trans("theme/front.create",[],$language->code) }}</h2>
        <form class="giris-from" wire:submit.prevent="submit">
            <div class="row">
                {{-- Başlık --}}
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.name', [], $language->code) }}</label></div>
                    <input type="text" wire:model.defer="title" class="tb">
                    @error('title')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3" style="padding-right: 0;">
                    <label>{{ trans('theme/front.age2', [], $language->code) }}</label>
                    <input type="number" wire:model.defer="age" class="tb" min="0">
                    @error('age')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label style="visibility: hidden">{{ trans('theme/front.age2', [], $language->code) }}</label>
                    <select wire:model.live="selectedUnit" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="month">{{ trans('theme/front.month', [], $language->code) }}</option>
                        <option value="year">{{ trans('theme/front.year', [], $language->code) }}</option>
                    </select>
                    @error('selectedUnit')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.gender2', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedGender" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.male', [], $language->code) }}">{{ trans('theme/front.male', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.female', [], $language->code) }}">{{ trans('theme/front.female', [], $language->code) }}</option>
                    </select>
                    @error('selectedGender')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.neutered2', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedNeutered" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.yes', [], $language->code) }}">
                            {{ trans('theme/front.yes', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.no', [], $language->code) }}">{{ trans('theme/front.no', [], $language->code) }}</option>
                    </select>
                    @error('selectedNeutered')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.apartment', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedApartment" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.yes', [], $language->code) }}">
                            {{ trans('theme/front.yes', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.no', [], $language->code) }}">{{ trans('theme/front.no', [], $language->code) }}</option>
                    </select>
                    @error('selectedApartment')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.other', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedOtherAnimals" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.yes', [], $language->code) }}">
                            {{ trans('theme/front.yes', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.no', [], $language->code) }}">{{ trans('theme/front.no', [], $language->code) }}</option>
                    </select>
                    @error('selectedOtherAnimals')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.size', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedSize" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.large', [], $language->code) }}">{{ trans('theme/front.large', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.medium', [], $language->code) }}">{{ trans('theme/front.medium', [], $language->code) }}</option>
                        <option value="{{ trans('theme/front.small', [], $language->code) }}">{{ trans('theme/front.small', [], $language->code) }}</option>
                    </select>
                    @error('selectedSize')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.photos', [], $language->code) }}</label></div>
                    <input type="file" multiple wire:model.defer="photos" required class="tb">
                    @error('photos.*')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.animal', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedCategory" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach (App\Models\Category::where('language_id', $language_id)->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCategory')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SubCategory Select --}}
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.breed', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedSubCategory" @disabled(!$subCategories->count()) class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach ($subCategories as $sc)
                            <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedSubCategory')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.city', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedCity" class="tb">
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCity')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.district', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedDistrict" class="tb" @disabled($districts->isEmpty())>
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedDistrict')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Mahalle → Quarter --}}
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.semt', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedNeigborhood" class="tb" @disabled($neigborhoods->isEmpty())>
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach ($neigborhoods as $n)
                            <option value="{{ $n->id }}">{{ $n->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedNeigborhood')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>{{ trans('theme/front.mahalle', [], $language->code) }}</label></div>
                    <select wire:model.live="selectedQuarter" class="tb" @disabled($quarters->isEmpty())>
                        <option value="">{{ trans('theme/front.choose', [], $language->code) }}</option>
                        @foreach ($quarters as $q)
                            <option value="{{ $q->id }}">{{ $q->name }} ({{ $q->postal_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('selectedQuarter')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <hr>
                {{-- Açıklama --}}
                <div class="mb-3">
                    <div><label>{{ trans('theme/front.description', [], $language->code) }}</label></div>
                    <textarea wire:model.defer="description" class="tb" style="min-height: 200px"></textarea>
                    @error('description')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Gönder --}}
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                {{ trans('theme/front.send', [], $language->code) }}
            </button>
        </form>
    </div>
    <script>
        window.addEventListener('swal', function(e) {
            const data = e.detail[0];

            Swal.fire({
                title: data.title,
                text: data.text || '',
                icon: data.icon,
                iconColor: data.iconColor,
                timer: 5000,
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: data.confirmButtonText,
            });
        });
    </script>

</div>
