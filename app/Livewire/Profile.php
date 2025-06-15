<?php

namespace App\Livewire;

use App;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Language;

class Profile extends Component
{
    use WithFileUploads;

    public $username;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $profile_photo;
    public $new_photo;
    public $listings;
    public $language_id;
    public $language;
    public $old_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount($language_id)
    {
        $user = Auth::guard('siteuser')->user();

        $this->username = $user->username;
        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->profile_photo = $user->profile_photo;
        $this->listings = $user->listings()->latest()->get();
        $this->language = Language::findOrFail($this->language_id);
    }

    protected function rules()
    {
        $userId = Auth::guard('siteuser')->id();

        return [
            'username' => ['required', 'alpha_dash', 'max:50', Rule::unique('site_users', 'username')->ignore($userId)],
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => ['required', 'email', Rule::unique('site_users', 'email')->ignore($userId)],
            'phone' => ['required', 'regex:/^[0-9\-\+\s]+$/', Rule::unique('site_users', 'phone')->ignore($userId)],
            'new_photo' => 'nullable|image|max:1024',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = Auth::guard('siteuser')->user();

        if ($this->new_photo) {
            $path = $this->new_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->update([
            'username' => $this->username,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('success', trans("theme/front.update_profile",[],$this->language->code));
        $this->dispatch('swal', [
            'title' => trans("theme/front.update_profile",[],$this->language->code),
            'confirmButtonText' => 'Tamam',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);
        $this->mount($this->language_id); // yeni photo path vs. güncellensin
    }

    protected function rulesForPassword()
    {
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    public function changePassword()
    {
        $this->validate($this->rulesForPassword());

        $user = Auth::guard('siteuser')->user();

        // Eski şifre kontrolü
        if (!Hash::check($this->old_password, $user->password)) {
            $this->addError('old_password', trans("theme/front.wrong_password",[],$this->language->code));
            return;
        }

        // Şifre güncelle
        $user->password = Hash::make($this->new_password);
        $user->save();

        // Formu sıfırla
        $this->old_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('success', trans("theme/front.updated_password",[],$this->language->code));
        $this->dispatch('swal', [
            'title' => trans("theme/front.updated_password",[],$this->language->code),
            'confirmButtonText' => 'Tamam',
            'icon' => 'success',
            'iconColor' => 'green',
        ]);
    }

    public function render()
    {
        App::setLocale($this->language->code);
        return view('livewire.profile', ['language' => $this->language]);
    }
}
