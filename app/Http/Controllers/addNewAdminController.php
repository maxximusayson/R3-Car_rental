<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class AddNewAdminController extends Controller
{
    /**
     * Register a new admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the form input
        $this->validator($request->all())->validate();

        // Create a new admin user
        $user = $this->create($request->all());

        // Handle avatar upload if necessary
        if ($request->hasFile('avatar_choose') && $request->file('avatar_choose')->isValid()) {
            $avatarName = $request->name . '-' . Str::random(10) . '.' . $request->file('avatar_choose')->extension();
            $avatarNameNospaces = preg_replace('/\s+/', '', $avatarName);
            $path = $request->file('avatar_choose')->storeAs('/images/avatars', $avatarNameNospaces);
            $user->avatar = '/' . $path;
            $user->save();
        } else {
            $user->avatar = $request->avatar_option;
            $user->save();
        }

        // Trigger the Registered event
        event(new Registered($user));

        // Redirect back to settings with a success message
        return redirect()->route('settings')->with('success', 'New admin added successfully.');
    }

    /**
     * Create a new admin user instance.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin', // Ensure this user is an admin
        ]);
    }

    /**
     * Validate the registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
