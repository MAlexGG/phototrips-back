<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::findPhotosByAuthUser();
        return response()->json($photos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:125",
            "description" => "required|max:500",
            "image" => "required|image",
            "city" => "required"
        ]);

        $user = Auth::user();
        
        $city = City::searchByName($request->city);

        if($city == null){
            return response()->json(["msg" => "Crea una ciudad para tu fotografía"]);
        }

        $photo = Photo::create([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $request->image,
            "user_id" => $user->id,
            "city_id" => $city->id
        ]);

        if($request->hasFile('image')){
            $photo['image'] = $request->file('image')->store('img', 'public');
        }

        $photo->save();

        return response()->json([
            "photo" => $photo, 
            "msg" => "La fotografía se ha creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $photo = Photo::findPhotoByAuthUser($id);

        if($photo == null){
            return response()->json([
                "msg" => "No tienes una fotografía con ese identificador"
            ], 200);
        } 

        return response()->json($photo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|max:125",
            "description" => "required|max:500",
            "image" => "required|max:500",
            "city" => "required"
        ]);

        $photo = Photo::findPhotoByAuthUser($id);

        $city = City::searchByName($request->city);

        if(!$photo){
            return response()->json(["msg" => "No tienes una fotografía con ese identificador"]);
        }

        if($city == null) {
            return response()->json(["msg" => "Crea una ciudad para tu fotografía"]);
        }

        $photo->update([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $request->image,
            "city_id" => $city->id
        ]);

        return response()->json(["msg" => "La fotografía se ha editado correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $photo = Photo::findPhotoByAuthUser($id);

        if($photo == null){
            return response()->json([
                "msg" => "No tienes una fotografía con ese identificador"
            ], 200);
        } 
        
        $photo->delete();
        return response()->json([
            "msg" => "La fotografía se ha borrado correctamente"
        ], 200);
    }

    public function showByCity(string $id)
    {
        $photos = Photo::findPhotosByCity($id);

        if(count($photos) == 0){
            return response()->json(["msg" => "No tienes fotografías en esa ciudad"]);
        }

        return response()->json($photos, 200);
    }
}
