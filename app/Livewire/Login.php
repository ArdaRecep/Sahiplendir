<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SiteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('siteuser')->attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/');
        }

        $this->addError('email', trans('credentials.invalid'));
    }

    public function render()
    {
        return view('livewire.login');
    }
}