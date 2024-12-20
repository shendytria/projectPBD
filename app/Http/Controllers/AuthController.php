<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function indexLogin(){
        return view('login');
    }
    public function indexRegister(){
        return view('register');
    }
    public function login(Request $request){

        $validatedData = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validatedData)) {
            return redirect()->route('admin.dashboard');
        } 

        return redirect()->back()->with('error', 'Oops, ada yang error coba lagi sesaat');

    }
    public function register(Request $request){

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),  // Hash the password
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenisUser_id' => 2,
            'status_user' => 'Active',
        ]);

        return response()->json(['success' => true, 'message' => 'Registration successful']);

    }
    public function logout(Request $request){

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('indexlogin');

    }
}
