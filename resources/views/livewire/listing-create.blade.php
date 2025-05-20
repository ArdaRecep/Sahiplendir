<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Yeni İlan Oluştur</h2>

    @if (session()->has('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="submit">
        {{-- Başlık --}}
        <div class="mb-4">
            <label>Başlık</label>
            <input type="text"
                   wire:model.defer="title"
                   class="w-full border rounded px-3 py-2">
            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Açıklama --}}
        <div class="mb-4">
            <label>Açıklama</label>
            <textarea wire:model.defer="description"
                      class="w-full border rounded px-3 py-2"></textarea>
            @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Durum --}}
        <div class="mb-4">
            <label>Durum</label>
            <select wire:model.defer="status" class="w-full border rounded px-3 py-2">
                <option value="active">Aktif</option>
                <option value="inactive">Pasif</option>
            </select>
        </div>
        <div class="mb-4">
            <label>Kategori</label>
            <select wire:model.defer="category_id" class="w-full border rounded px-3 py-2">
                <option value="">Seçiniz</option>
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
            </select>
            @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Şehir → İlçe --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label>Şehir</label>
                <select wire:model.live="selectedCity"
                        class="w-full border rounded px-3 py-2">
                    <option value="">Seçiniz</option>
                    @foreach ($cities as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('selectedCity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label>İlçe</label>
                <select wire:model.live="selectedDistrict"
                        class="w-full border rounded px-3 py-2"
                        @disabled($districts->isEmpty())>
                    <option value="">Seçiniz</option>
                    @foreach ($districts as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                @error('selectedDistrict') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Mahalle → Quarter --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label>Mahalle</label>
                <select wire:model.live="selectedNeigborhood"
                        class="w-full border rounded px-3 py-2"
                        @disabled($neigborhoods->isEmpty())>
                    <option value="">Seçiniz</option>
                    @foreach ($neigborhoods as $n)
                        <option value="{{ $n->id }}">{{ $n->name }}</option>
                    @endforeach
                </select>
                @error('selectedNeigborhood') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label>Quarter</label>
                <select wire:model.live="selectedQuarter"
                        class="w-full border rounded px-3 py-2"
                        @disabled($quarters->isEmpty())>
                    <option value="">Seçiniz</option>
                    @foreach ($quarters as $q)
                        <option value="{{ $q->id }}">{{ $q->name }} ({{ $q->postal_code }})</option>
                    @endforeach
                </select>
                @error('selectedQuarter') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Gönder --}}
        <button type="submit"
                class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded">
            İlanı Yayınla
        </button>
    </form>
</div>
