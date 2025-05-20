<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4">Giriş Yap</h2>
        <form class="giris-from" wire:submit.prevent="login">
            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" id="email" type="email" wire:model.defer="email" placeholder="Email" />
            @error('password')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" id="password" type="password" wire:model.defer="password" placeholder="Şifreniz" />
            <button class="btn btn-primary" type="submit">Giriş Yap</button>
            <div class="flex items-center mb-4 pt-3">
                <input id="remember" type="checkbox" wire:model.defer="remember" class="mr-2">
                <label for="remember">Beni Hatırla</label>
            </div>
            <p class="mesaj">Üye Değil Misin ? <a href="/tr/kayit-ol">Hesap Oluştur</a></p>
        </form>
    </div>
</div>
