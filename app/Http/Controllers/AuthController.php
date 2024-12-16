<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function registerForm()
    {
        return view('auth.register');  
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $user->role_id = 2;
        $user->save();
        Auth::login($user);
        return redirect()->route('login.form')->with('success', 'Registration successful! Please login.');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function loginForm()
    {
        return view('auth.login');  
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }   
        \Log::info($request->all()); 
        
        if (Auth::attempt([
            'email' => $request->email, 
            'password' => $request->password
        ])) {
            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('profile');
            }
        } else {
            return redirect()->back()->withErrors(['message' => 'Invalid credentials.'])->withInput();
        }
    }        
}
