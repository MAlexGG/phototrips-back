<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photos = Photo::findPhotosByAuthUser();
        if(count($photos) == 0){
            return response()->json(["msg" => "No tienes aún fotografías cargadas"]);
        }
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
            "image" => "image",
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

        $destination = public_path("storage\\" . $photo->image);
        $filename = '';

        if ($request->hasFile('image')) {
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $filename = $request->file('image')->store('img', 'public');
        } else {
            $filename = $photo->image;
        }

        $photo->update([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $filename,
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

        $destination = public_path("storage\\" . $photo->image);
        
        $photo->delete();
        File::delete($destination);
        
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
