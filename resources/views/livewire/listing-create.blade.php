<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4" style="text-align: center">İlan Oluştur</h2>
        <form class="giris-from" wire:submit.prevent="submit">
            <div class="row">
                {{-- Başlık --}}
                <div class="col-md-6 mb-3">
                    <div><label>İsmi</label></div>
                    <input type="text" wire:model.defer="title" class="tb">
                    @error('title')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3" style="padding-right: 0;">
                    <label>Yaşı</label>
                    <input type="number" wire:model.defer="age" class="tb" min="0">
                    @error('age')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label style="visibility: hidden">Yaşı</label>
                    <select wire:model.live="selectedUnit" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="month">Aylık</option>
                        <option value="year">Yıl</option>
                    </select>
                    @error('selectedUnit')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Cinsiyeti</label></div>
                    <select wire:model.live="selectedGender" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="Erkek">Erkek</option>
                        <option value="Dişi">Dişi</option>
                    </select>
                    @error('selectedGender')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Kısır Mı?</label></div>
                    <select wire:model.live="selectedNeutered" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
                    </select>
                    @error('selectedNeutered')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Apartman Dairesinde Yaşar</label></div>
                    <select wire:model.live="selectedApartment" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
                        <option value="Emin Değilim">Emin Değilim</option>
                    </select>
                    @error('selectedApartment')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Diğer Hayvanlarla Anlaşır</label></div>
                    <select wire:model.live="selectedOtherAnimals" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="Evet">Evet</option>
                        <option value="Hayır">Hayır</option>
                        <option value="Emin Değilim">Emin Değilim</option>
                    </select>
                    @error('selectedOtherAnimals')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Boyutu</label></div>
                    <select wire:model.live="selectedSize" class="tb">
                        <option value="">Seçiniz</option>
                        <option value="Büyük">Büyük</option>
                        <option value="Orta">Orta</option>
                        <option value="Küçük">Küçük</option>
                    </select>
                    @error('selectedSize')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Resimleri</label></div>
                    <input type="file" multiple wire:model.defer="photos" required class="tb">
                    @error('photos.*')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div><label>Türü</label></div>
                    <select wire:model.live="selectedCategory" class="tb">
                        <option value="">Seçiniz</option>
                        @foreach (\App\Models\Category::all() as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCategory')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SubCategory Select --}}
                <div class="col-md-6 mb-3">
                    <div><label>Cinsi</label></div>
                    <select wire:model.live="selectedSubCategory" @disabled(!$subCategories->count()) class="tb">
                        <option value="">Seçiniz</option>
                        @foreach ($subCategories as $sc)
                            <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedSubCategory')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>Şehir</label></div>
                    <select wire:model.live="selectedCity" class="tb">
                        <option value="">Seçiniz</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedCity')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>İlçe</label></div>
                    <select wire:model.live="selectedDistrict" class="tb" @disabled($districts->isEmpty())>
                        <option value="">Seçiniz</option>
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
                    <div><label>Semt</label></div>
                    <select wire:model.live="selectedNeigborhood" class="tb" @disabled($neigborhoods->isEmpty())>
                        <option value="">Seçiniz</option>
                        @foreach ($neigborhoods as $n)
                            <option value="{{ $n->id }}">{{ $n->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedNeigborhood')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div><label>Mahalle</label></div>
                    <select wire:model.live="selectedQuarter" class="tb" @disabled($quarters->isEmpty())>
                        <option value="">Seçiniz</option>
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
                    <div><label>Açıklama</label></div>
                    <textarea wire:model.defer="description" class="tb" style="min-height: 200px"></textarea>
                    @error('description')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Gönder --}}
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                İlanı Gönder
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
