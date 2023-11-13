<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();

        $posts = $user->publicPosts()->orderBy('date', 'desc')->get();

        //$this->authorize('show', $user);

        return view('pages.profile', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function edit(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return view('pages.profile_edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, string $username)
    {
        // Validate the form data
        $request->validate([
            'display_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'academic_status' => 'required|in:student,teacher',
            'privacy' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the user by username
        $user = User::where('username', $username)->firstOrFail();

        // Update the user information
        $user->display_name = $request->input('display_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->academic_status = $request->input('academic_status');
        $user->is_private = $request->input('privacy') === 'private';

        // Update the password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->image = asset('storage/' . $imagePath);
        }

        // Save the changes
        $user->save();

        return redirect()->route('profile', ['username' => $user->username])
            ->with('success', 'Profile updated successfully!');
    }
}
