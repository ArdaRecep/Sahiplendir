<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SiteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class Register extends Component
{
    use WithFileUploads;

    // Form fields
    public $username;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $profile_photo;

    protected function rules()
    {
        return [
            'username'                => 'required|alpha_dash|max:50|unique:site_users,username',
            'name'                    => 'required|string|max:50',
            'surname'                 => 'required|string|max:50',
            'email'                   => 'required|email|unique:site_users,email',
            'phone'                   => 'required|regex:/^[0-9\-\+\s]+$/|unique:site_users,phone',
            'password'                => ['required', 'confirmed', Password::min(8)],
            'profile_photo'           => 'nullable|image|max:2048',
        ];
    }

    public function save()
    {
        $this->validate();

        // Handle profile photo upload
        $photoPath = null;
        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile-photos', 'public');
        }

        // Create user
        $user = SiteUser::create([
            'username'      => $this->username,
            'name'          => $this->name,
            'surname'       => $this->surname,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'password'      => bcrypt($this->password),
            'profile_photo' => $photoPath,
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.register');
    }
}