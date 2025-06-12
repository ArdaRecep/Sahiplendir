<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        Auth::guard('siteuser')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/tr');
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
