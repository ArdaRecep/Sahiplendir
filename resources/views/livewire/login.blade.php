<section class="max-w-md mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Giriş Yap</h2>

    <form wire:submit.prevent="login">
        <div class="mb-4">
            <label for="email">E-Posta</label>
            <input id="email" type="email" wire:model.defer="email" class="block w-full border rounded px-3 py-2">
            @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="password">Şifre</label>
            <input id="password" type="password" wire:model.defer="password" class="block w-full border rounded px-3 py-2">
            @error('password') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center mb-4">
            <input id="remember" type="checkbox" wire:model.defer="remember" class="mr-2">
            <label for="remember">Beni Hatırla</label>
        </div>

        <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded">
            Giriş Yap
        </button>
    </form>
</section>