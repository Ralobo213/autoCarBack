<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SuperAdminController extends Controller
{

    public function Users()
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'Job' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'photo' => 'nullable|image|max:2048', // Taille maximale de 2MB pour l'image
                'Role' => 'string|in:Admin,SuperAdmin', // Assurez-vous que Role est valide
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
                'Job' => $request->job,
                'Role' => $request->input('Role', 'Admin'), // Définir 'Admin' comme rôle par défaut
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
                'password' => 'nullable|string|min:8',
                'Job' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'photo' => 'nullable|image|max:2048',
                'Role' => 'string|in:Admin,SuperAdmin',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Trouver l'utilisateur à mettre à jour
            $user = User::findOrFail($id);

            // Mettre à jour les champs seulement s'ils existent
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
            if ($request->has('Role')) {
                $user->Role = $request->Role;
            }

            // Mettre à jour la photo si une nouvelle photo est téléchargée
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->photo) {
                    Storage::delete('public/' . $user->photo);
                }

                // Enregistrer la nouvelle photo
                $photo = $request->file('photo');
                $photoPath = $photo->store('photos', 'public');
                $user->photo = $photoPath;
            }

            // Sauvegarder les modifications
            $user->save();

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l’utilisateur: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la mise à jour'], 500);
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
    public function show($id)
    {
        try {
            // Valider que l'ID est bien un entier
            if (!is_numeric($id) || (int)$id <= 0) {
                return response()->json(['message' => 'Invalid ID provided'], 400);
            }
    
            // Trouver l'utilisateur par son ID
            $user = User::find($id);
    
            // Vérifier si l'utilisateur existe
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            // Retourner les détails de l'utilisateur sous forme de JSON
            return response()->json($user);
        } catch (\Exception $e) {
            // Retourner un message d'erreur en cas d'exception
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function CountUser()
    {
        try{
            $counter = User::count();
            $vehi = Vehicule::count();
            return response()->json([
                'counter' => $counter,
                'vehi' => $vehi
            ]);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'une erreur'.$e->getMessage()], 500);
        }
    }

    public function reservation(Request $req , $id){
        try {
            // Valider les données de la requête
            $validator = Validator::make($req->all(), [
                'DateDebut' => 'required|string|max:200',
                'DateFin' => 'required|string|max:200',
                'PriceTotal' => 'bail|nullable'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // creation reservation
            $reservation = new Reservation();
            $reservation->id_client = $req->id_client;
            $reservation->id_voiture = $id;
            $reservation->DateDebut = $req->DateDebut;
            $reservation->DateFin = $req->DateFin;
            $reservation->PriceTotal = $req->Price;
            $reservation -> save();
            return response()->json('succes','Reservation succes');
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
