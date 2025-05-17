<section class="max-w-md mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Kayıt Ol</h2>

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label>Kullanıcı Adı</label>
            <input type="text" wire:model.blur="username" class="block w-full border rounded px-3 py-2">
            @error('username') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>Ad</label>
            <input type="text" wire:model.blur="name" class="block w-full border rounded px-3 py-2">
            @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>Soyad</label>
            <input type="text" wire:model.blur="surname" class="block w-full border rounded px-3 py-2">
            @error('surname') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>E-Posta</label>
            <input type="email" wire:model.blur="email" class="block w-full border rounded px-3 py-2">
            @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>Telefon</label>
            <input type="tel" wire:model.blur="phone" class="block w-full border rounded px-3 py-2">
            @error('phone') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>Şifre</label>
            <input type="password" wire:model.blur="password" class="block w-full border rounded px-3 py-2">
            @error('password') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label>Şifre (Tekrar)</label>
            <input type="password" wire:model.blur="password_confirmation" class="block w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label>Profil Resmi</label>
            <input type="file" wire:model="profile_photo" class="block w-full border rounded px-3 py-2">
            @error('profile_photo') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <button type="submit"
                class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded">
            Kayıt Ol
        </button>
    </form>
</section>
