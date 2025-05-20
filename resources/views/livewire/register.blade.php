<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4">Kayıt Ol</h2>
        @error('username')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
        <form class="giris-from" wire:submit.prevent="save">
            <input class="tb" type="text" wire:model.blur="username" name="username" placeholder="Kullanıcı Adı"
                class="block w-full border rounded px-3 py-2">

            @error('name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="name" name="name" placeholder="Ad"
                class="block w-full border rounded px-3 py-2">

            @error('surname')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="surname" name="surname" placeholder="Soyad"
                class="block w-full border rounded px-3 py-2">

            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="email" name="email" placeholder="Email"
                class="block w-full border rounded px-3 py-2">

            @error('phone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="tel" wire:model.blur="phone" name="phone" placeholder="Telefon"
                class="block w-full border rounded px-3 py-2">

            @error('password')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="password" wire:model.blur="password" name="password" placeholder="Şifre"
                class="block w-full border rounded px-3 py-2">
            <input class="tb" type="password" wire:model.blur="password_confirmation" name="password_confirmation"
                placeholder="Şifre (Tekrar)" class="block w-full border rounded px-3 py-2">

            @error('profile_photo')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input type="file" wire:model="profile_photo" name="profile_photo"
                class="block w-full border rounded px-3 py-2">


            <button type="submit" class="btn btn-primary">
                Kayıt Ol
            </button>
        </form>
    </div>
</div>
