<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function index()
    {
        return view('settings.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Obecne hasło jest nieprawidłowe.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Hasło zostało zmienione.');
    }
}


