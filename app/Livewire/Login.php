<?php

namespace App\Livewire;

use App;
use App\Models\Language;
use Livewire\Component;
use App\Models\SiteUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $language;
    public $email;
    public $password;
    public $remember = false;

    public function mount($language_id)
    {
        $this->language = Language::findOrFail($language_id);
    }

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

        $this->addError('email', trans("validation.invalid", [], $this->language->code));
    }

    public function render()
    {
        App::setLocale($this->language->code);
        return view('livewire.login', ['language' => $this->language]);
    }
}