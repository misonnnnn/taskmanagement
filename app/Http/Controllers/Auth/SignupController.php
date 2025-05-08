<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SignupController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showSignup(): View
    {
        return view('Auth.signup');
    }

    public function signup(Request $request): RedirectResponse
    {

        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $newUser = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($newUser);

        auth()->login($user);

        return redirect()->route('home')->with('success', 'Succesfully created account! ');
    }
}
