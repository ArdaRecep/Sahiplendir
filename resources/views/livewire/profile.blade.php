<div>
    <style>
        .nav-tabs .nav-link.active {
            background: #fa7817;
        }

        .inp {
            width: 270px
        }
    </style>
    <section class="section section--head" style="padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-6">
                    <h1 class="section__title section__title--head">Profil</h1>
                    @if (session()->has('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif
                </div>
            </div>
    </section>
    <!-- end head -->
    <!-- profile -->
    <div class="catalog catalog--page" style="margin-bottom: 100px">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="profile">
                        <!-- tabs nav -->
                        <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab"
                                    aria-controls="tab-1" aria-selected="true">Profil</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab"
                                    aria-controls="tab-2" aria-selected="false">İlanlarım</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- content tabs -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="sign__wrap">
                                <div class="row justify-content-center">
                                    <form wire:submit.prevent="save" enctype="multipart/form-data"
                                        style="width: 450px;">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if ($profile_photo)
                                                <img src="{{ Storage::url($profile_photo) }}" alt="Profil Fotoğrafı"
                                                    class="w-16 h-16 rounded-full mr-4"
                                                    style="width:128px;height:128px;">
                                            @endif
                                        </div>
                                        <div style="display: flex;justify-content: center;">

                                            <input type="file" id="new_photo" wire:model="new_photo"
                                                class="mt-4 mb-4" style="width: 200px;">
                                            @error('new_photo')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4" style="display: flex;justify-content: space-between;">
                                            <label>Kullanıcı Adı:</label>
                                            <input type="text" wire:model.defer="username"
                                                class="block w-full border rounded px-3 py-2 inp">
                                            @error('username')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4" style="display: flex;justify-content: space-between;">
                                            <label>Ad:</label>
                                            <input type="text" wire:model.defer="name"
                                                class="block w-full border rounded px-3 py-2 inp">
                                            @error('name')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4" style="display: flex;justify-content: space-between;">
                                            <label>Soyad:</label>
                                            <input type="text" wire:model.defer="surname"
                                                class="block w-full border rounded px-3 py-2 inp">
                                            @error('surname')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4" style="display: flex;justify-content: space-between;">
                                            <label>E-Posta:</label>
                                            <input type="email" wire:model.defer="email"
                                                class="block w-full border rounded px-3 py-2 inp">
                                            @error('email')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4" style="display: flex;justify-content: space-between;">
                                            <label>Telefon:</label>
                                            <input type="tel" wire:model.defer="phone"
                                                class="block w-full border rounded px-3 py-2 inp">
                                            @error('phone')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                                            Bilgileri Güncelle
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-2" role="tabpanel">
                    <!-- favorites -->
                    <div class="row row--grid">

                        @if ($listings->isEmpty())
                            <h3 class="card__title" style="margin-left: 25px;">Henüz ilan vermediniz</h3>
                        @else
                            @foreach ($listings as $listing)
                                <div class="col-12 col-md-6 col-lg-4"
                                    style="margin-bottom: 30px; @if ($listing->status == 'inactive') opacity: 0.6; @endif">

                                    <div class="card">
                                        @php
                                        $listingLang = App\Models\Language::findOrFail($listing->language_id); @endphp
                                        @if ($listing->status == 'active')
                                            <a href="/{{ $listingLang->code }}/{{ $listing->listing_no }}"
                                                class="card__link">
                                        @endif
                                        <div class="card__cover" style="height: 300px;">
                                            {{-- Örneğin listing modelinizde image alanı varsa --}}
                                            @if (isset($listing->photos[0]))
                                                <img class="border-irregular1 img-fluid"
                                                    src="{{ Storage::url($listing->photos[0]) }}"
                                                    alt="{{ $listing->title }}" style="height: 275px;width: 300px;">
                                            @endif
                                        </div>

                                        <div class="card__content">
                                            <h3 class="card__title">{{ Str::limit($listing->title, 12) }}</h3>
                                            <p class="card__title">
                                                {{ Str::limit($listing->description, 28) }}
                                            </p>
                                            <p class="card__title">
                                                {{ $listingLang->name }}
                                                @if ($listing->status == 'inactive')
                                                    <span>\ Onay Bekliyor...</span>
                                                @endif
                                            </p>
                                        </div>
                                        @if ($listing->status == 'active')
                                            </a>
                                        @endif
                                    </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <!-- end favorites -->
                </div>

            </div>
            <!-- end content tabs -->
        </div>
    </div>
    <!-- end profile -->
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
