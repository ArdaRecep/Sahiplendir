<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4">{{ trans("theme/front.login2",[],$language->code) }}</h2>
        <form class="giris-from" wire:submit.prevent="login">
            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" id="email" type="email" wire:model.defer="email" placeholder="{{ trans("theme/front.email",[],$language->code) }}" />
            @error('password')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" id="password" type="password" wire:model.defer="password" placeholder="{{ trans("theme/front.password",[],$language->code) }}" />
            <button class="btn btn-primary" type="submit">{{ trans("theme/front.login2") }}</button>
            <div class="flex items-center mb-4 pt-3">
                <input id="remember" type="checkbox" wire:model.defer="remember" class="mr-2">
                <label for="remember">{{ trans("theme/front.remember",[],$language->code) }}</label>
            </div>
            <p class="mesaj">{{ trans("theme/front.not_member",[],$language->code) }} <a href="{{ trans("theme/front.register_link",[],$language->code) }}">{{ trans("theme/front.create_account",[],$language->code) }}</a></p>
        </form>
    </div>
</div>
