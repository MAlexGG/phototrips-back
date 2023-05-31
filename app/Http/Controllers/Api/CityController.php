<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::all();
        return response()->json($cities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "country" => "required"
        ]);

        $citySearched = City::searchByName($request->name);

        $country = Country::searchByName($request->country);
        
        if($citySearched){
            return response()->json(["msg" => "La ciudad ya existe"]);
        }

        if($country == null){
            return response()->json(["msg" => "Crea un país para tu fotografía"]);
        }

        $city = City::create([
            "name" => $request->name,
            "country_id" => $country->id
        ]);

        $city->save();

        return response()->json([
            "city" => $city,
            "msg" => "La ciudad se ha creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $city = City::find($id);
        return response()->json($city, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|max:255",
            "country" => "required"
        ]);

        $citySearched = City::searchByName($request->name);

        $country = Country::searchByName($request->country);
        
        if($citySearched){
            return response()->json(["msg" => "La ciudad ya existe en la base de datos"]);
        }

        if($country == null){
            return response()->json(["msg" => "Crea un país para tu fotografía"]);
        }

        City::find($id)->update([
            "name" => $request->name,
            "country_id" => $country->id
        ]);

        return response()->json([
            "msg" => "La ciudad se ha actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $city = City::find($id);
        $city->delete();

        return response()->json(["msg" => "La ciudad se ha eliminado correctamente"]);
    }
}
