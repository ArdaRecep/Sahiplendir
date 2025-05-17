<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteUser;

class SectionSubmissionController extends Controller
{
    public function handle(Request $request, $lang = null, $slug)
    {
        switch ($slug) {
            case 'register':
                $data = $request->validate([
                    'name'                  => 'required|string|max:50',
                    'surname'               => 'required|string|max:50',
                    'email'                 => 'required|email|unique:site_users,email',
                    'password'              => 'required|string|confirmed|min:8',
                ]);
                $user = SiteUser::create([
                    'name'     => $data['name'],
                    'surname'  => $data['surname'],
                    'email'    => $data['email'],
                    'password' => bcrypt($data['password']),
                ]);
                Auth::login($user);
                return redirect()->intended('/');

            case 'login':
                $credentials = $request->validate([
                    'email'    => 'required|email',
                    'password' => 'required|string',
                ]);
                if (Auth::attempt($credentials, $request->boolean('remember'))) {
                    $request->session()->regenerate();
                    return redirect()->intended('/');
                }
                return back()
                    ->withErrors(['email' => __('credentials.invalid')])
                    ->onlyInput('email');

            // Diğer slug’lar için ek case’ler…

            default:
                abort(404);
        }
    }
}
