<?php

namespace App\Livewire;

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

    public function mount($language_id)
    {
        $user = Auth::guard('siteuser')->user();

        $this->username      = $user->username;
        $this->name          = $user->name;
        $this->surname       = $user->surname;
        $this->email         = $user->email;
        $this->phone         = $user->phone;
        $this->profile_photo = $user->profile_photo;
        $this->listings = $user->listings()->latest()->get();
    }

    protected function rules()
    {
        $userId = Auth::guard('siteuser')->id();

        return [
            'username'   => ['required','alpha_dash','max:50', Rule::unique('site_users','username')->ignore($userId)],
            'name'       => 'required|string|max:50',
            'surname'    => 'required|string|max:50',
            'email'      => ['required','email', Rule::unique('site_users','email')->ignore($userId)],
            'phone'      => ['nullable','regex:/^[0-9\-\+\s]+$/', Rule::unique('site_users','phone')->ignore($userId)],
            'new_photo'  => 'nullable|image|max:1024',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = Auth::guard('siteuser')->user();

        if ($this->new_photo) {
            $path = $this->new_photo->store('profile-photos','public');
            $user->profile_photo = $path;
        }

        $user->update([
            'username' => $this->username,
            'name'     => $this->name,
            'surname'  => $this->surname,
            'email'    => $this->email,
            'phone'    => $this->phone,
        ]);

        session()->flash('success', 'Profiliniz güncellendi.');
        $this->dispatch('swal', [
                'title' => 'Profiliniz güncellendi',
                'confirmButtonText' => 'Tamam',
                'icon' => 'success',
                'iconColor' => 'green',
            ]);
        $this->mount($this->language_id); // yeni photo path vs. güncellensin
    }

    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        return view('livewire.profile',['language' => $language]);
    }
}
