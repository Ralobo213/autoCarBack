<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'firstname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                'Job' => ['required', 'string', 'max:255'],
                'contact' => ['nullable', 'string', 'max:255'],
                'photo' => ['nullable', 'string', 'max:255'],
                'Role' => ['string', 'in:Admin,SuperAdmin'], // Assurez-vous que Role est valide
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'firstname' => $request->firstname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'Job' => $request->Job,
                'Role' => $request->input('Role', 'Admin'), // Définir 'Admin' comme rôle par défaut
                'contact' => $request->contact,
                'photo' => $request->photo,
            ]);
    
            event(new Registered($user));
    
            Auth::login($user);
    
            // Retourner une réponse JSON avec un message de succès
            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Exception $e) {
            // Retourner une réponse JSON en cas d'erreur
            return response()->json(['error' => 'Registration failed. Please try again.', 'messageError' => $e->getMessage()], 500);
        }
    }
}    