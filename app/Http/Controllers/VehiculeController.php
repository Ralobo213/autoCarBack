<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehiculeController extends Controller
{
// insertion des vehicules
    public function StoreCar(Request $req)
    {
        try {
    $req->validate([
        'photo' => 'required|image|max:2048',
        'marque' => 'required|string|max:255',
        'matricule' => 'required|string|max:255',
        'description' => 'required',
        'prix' => 'required',
        'porte' => 'required',
        'place' => 'required',
        'bagage' => 'required',
    ]);
    if ($req->hasFile('photo')) {
        $photo = $req->file('photo');
        $photoName = time().'.'.$photo->getClientOriginalExtension();
        $photoPath = $photo->storeAs('ImageVehicule', $photoName, 'public');

        $InsertVehicul = new Vehicule();
        $InsertVehicul->photo = $photoName;
        $InsertVehicul->marque = $req->marque;
        $InsertVehicul->matricule = $req->matricule;
        $InsertVehicul->description = $req->description;
        $InsertVehicul->prix = $req->prix;
        $InsertVehicul->porte = $req->porte;
        $InsertVehicul->place = $req->place;
        $InsertVehicul->bagage = $req->bagage;
        $InsertVehicul->save();

        return response()->json([
            'message' => 'un vehicule est inseret avec succes!',
            'file_path' => "/storage/$photoPath",
            'path' => Storage::url($photoPath),
        ]);
    } else {
        return response()->json([
            'error' => 'veuillez inseret un photo!'
        ], 400);
    }
        } catch (\Exception $e) {
            return response()->json([
                'messageError' => $e->getMessage(),
            ], 500);
        }


    }
    //
    //affichage des vehicules
    public function viewVehicule()
    {
        try {
            $vehicules = Vehicule::all();
            return response()->json([
                'vehicules' => $vehicules,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([

                'messageError' => $e->getMessage(),
            ], 500);
        }

    }

    //suppression d'un vehicule
    public function deleteCar($id)
    {
        // recuperation id
        $getId = Vehicule::find($id);

        $getId->delete();

        return response()->json([
            'message' => 'suprrimer',
        ], 200);
    }

    //modification des vehicules
    public function updateCar(Request $req, $id)
    {

        try {
            $req->validate([
                'marque' => 'required',
                'matricule' => 'required',
                'photo' => 'required',
                'description' => 'required',
                'prix' => 'required',
                'porte' => 'required',
                'place' => 'required',
                'bagage' => 'required',
            ]);
            //recuperation de l'id
            $InsertVehicul = Vehicule::find($id);

            //modification du photo
            if ($req->hasFile('photo')) {
                $photo = $req->file('photo');
                $photoName = time().'.'.$photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('ImageVehicule', $photoName, 'public');
                $InsertVehicul->photo = $photoName;

            }

            //modifiation des donnes
            $InsertVehicul->marque = $req->marque;
            $InsertVehicul->matricule = $req->matricule;
            $InsertVehicul->description = $req->description;
            $InsertVehicul->prix = $req->prix;
            $InsertVehicul->porte = $req->porte;
            $InsertVehicul->place = $req->place;
            $InsertVehicul->bagage = $req->bagage;
            $InsertVehicul->update();

            return response()->json([
                'message' => 'modiifer!',
                'vehicule' => $InsertVehicul,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'messageError' => $e->getMessage(),
            ], 500);
        }
    }

}
