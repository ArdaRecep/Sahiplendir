<div>
    <section class="section section--head">
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
    <div class="catalog catalog--page">
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
                                    aria-controls="tab-2" aria-selected="false">İlanlar</a>
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
                                <div class="row">
                                    <form wire:submit.prevent="save" enctype="multipart/form-data">
                                        <div class="flex items-center">
                                            @if ($profile_photo)
                                                <img src="{{ Storage::url($profile_photo) }}" alt="Profil Fotoğrafı"
                                                    class="w-16 h-16 rounded-full mr-4"
                                                    style="width:128px;height:128px;">
                                            @endif
                                            <div>
                                                <label for="new_photo" class="block text-sm font-medium">Yeni
                                                    Fotoğraf</label>
                                                <input type="file" id="new_photo" wire:model="new_photo"
                                                    class="mt-1">
                                                @error('new_photo')
                                                    <div class="text-red-600 text-sm">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label>Kullanıcı Adı</label>
                                            <input type="text" wire:model.defer="username"
                                                class="block w-full border rounded px-3 py-2">
                                            @error('username')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label>Ad</label>
                                            <input type="text" wire:model.defer="name"
                                                class="block w-full border rounded px-3 py-2">
                                            @error('name')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label>Soyad</label>
                                            <input type="text" wire:model.defer="surname"
                                                class="block w-full border rounded px-3 py-2">
                                            @error('surname')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label>E-Posta</label>
                                            <input type="email" wire:model.defer="email"
                                                class="block w-full border rounded px-3 py-2">
                                            @error('email')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label>Telefon</label>
                                            <input type="tel" wire:model.defer="phone"
                                                class="block w-full border rounded px-3 py-2">
                                            @error('phone')
                                                <div class="text-red-600 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">
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


                        <h3 class="card__title" style="margin-left: 25px;">Henüz ilan vermediniz</h3>

                    </div>
                    <!-- end favorites -->
                </div>

            </div>
            <!-- end content tabs -->
        </div>
    </div>
    <!-- end profile -->
</div>
