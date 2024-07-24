<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{

    public function register(Request $request)
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'job' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'photo' => 'nullable|image|max:2048', // Taille maximale de 2MB pour l'image
                'role' => 'string|in:Admin,SuperAdmin', // Assurez-vous que Role est valide
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Enregistrement de la photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoPath = $photo->store('photos', 'public'); // Enregistrer le fichier dans storage/app/public/photos
            }

            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'firstname' => $request->firstname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'job' => $request->job,
                'role' => $request->input('role', 'Admin'), // Définir 'Admin' comme rôle par défaut
                'contact' => $request->contact,
                'photo' => $photoPath, // Chemin d'accès au fichier de photo enregistré
            ]);

            return response()->json(['message' => 'User created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500); // Capture et retourne l'erreur
        }
    }
    public function updateUser(Request $request, $id)
    {
        try {
            // Valider les données de la requête
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'firstname' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email,' . $id,
                'password' => 'string|min:8|confirmed',
                'Job' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'photo' => 'nullable|string|max:255',
                'Role' => 'string|in:Admin,SuperAdmin', // Assurez-vous que Role est valide
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Trouver l'utilisateur à mettre à jour
            $user = User::findOrFail($id);

            // Mettre à jour les champs seulement si ils existent
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('firstname')) {
                $user->firstname = $request->firstname;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            if ($request->has('Job')) {
                $user->Job = $request->Job;
            }
            if ($request->has('contact')) {
                $user->contact = $request->contact;
            }
            if ($request->has('photo')) {
                $user->photo = $request->photo;
            }
            if ($request->has('Role')) {
                $user->Role = $request->Role;
            }

            // Sauvegarder les modifications
            $user->save();

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function deleteUser($id)
    {
        try {
            // Trouver l'utilisateur à supprimer
            $user = User::findOrFail($id);

            // Supprimer l'utilisateur
            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
