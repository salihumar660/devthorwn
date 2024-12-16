<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role_id == 1) {
            return view('dashboard', ['full_dashboard' => true, 'user' => $user]);
        }
        return view('dashboard', ['full_dashboard' => false, 'user' => $user]);
    }

    /**
     * Show the user's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = auth()->user();
        if ($user->role_id == 2) {
        return view('dashboard', ['full_dashboard' => false, 'user' => $user]);
        }
        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }


    /**
     * Handle the logout action.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
{
    Auth::logout();
    return redirect('/');
}

}
