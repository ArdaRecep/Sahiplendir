<div class="giris">
    <div class="form">
        <h2 class="text-xl font-semibold mb-4">{{ trans("theme/front.register",[],$language->code) }}</h2>
        @error('username')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
        <form class="giris-from" wire:submit.prevent="save">
            <input class="tb" type="text" wire:model.blur="username" name="username" placeholder="{{ trans("theme/front.username",[],$language->code) }}"
                class="block w-full border rounded px-3 py-2">

            @error('name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="name" name="name" placeholder="{{ trans("theme/front.name",[],$language->code) }}"
                class="block w-full border rounded px-3 py-2">

            @error('surname')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="surname" name="surname" placeholder="{{ trans("theme/front.surname",[],$language->code) }}"
                class="block w-full border rounded px-3 py-2">

            @error('email')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="text" wire:model.blur="email" name="email" placeholder="{{ trans("theme/front.email",[],$language->code) }}"
                class="block w-full border rounded px-3 py-2">

            @error('phone')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="tel" wire:model.blur="phone" name="phone" placeholder="{{ trans("theme/front.phone",[],$language->code) }} (0...)"
                class="block w-full border rounded px-3 py-2">

            @error('password')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input class="tb" type="password" wire:model.blur="password" name="password" placeholder="{{ trans("theme/front.password",[],$language->code) }}"
                class="block w-full border rounded px-3 py-2">
            <input class="tb" type="password" wire:model.blur="password_confirmation" name="password_confirmation"
                placeholder="{{ trans("theme/front.password_again",[],$language->code) }}" class="block w-full border rounded px-3 py-2">

            @error('profile_photo')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <input type="file" wire:model="profile_photo" name="profile_photo"
                class="block w-full border rounded px-3 py-2">


            <button type="submit" class="btn btn-primary">
                {{ trans("theme/front.register",[],$language->code) }}
            </button>
        </form>
    </div>
</div>
