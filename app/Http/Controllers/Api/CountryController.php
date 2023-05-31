<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Continent;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        return response()->json($countries, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "continent" => "required"
        ]);

        $countrySearched = Country::searchByName($request->name);

        $continent = Continent::searchByName($request->continent);

        if($countrySearched){
            return response()->json(["msg" => "El país ya existe"]);
        }

        if($continent == null){
            return response()->json(["msg" => "Crea un continente para tu fotografía"]);
        }

        $country = Country::create([
            "name" => $request->name,
            "continent_id" => $continent->id 
        ]);

        $country->save();

        return response()->json([
            "country" => $country,
            "msg" => "El país se ha creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country = Country::find($id);

        if(!$country){
            return response()->json(["msg" => "El país no existe en la base de datos"]);
        }

        return response()->json($country, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required|max:255",
            "continent" => "required"
        ]);

        $countrySearched = Country::searchByName($request->name);

        $continent = Continent::searchByName($request->continent); 

        if($countrySearched){
            return response()->json(["msg" => "El país ya existe en la base de datos"]);
        }

        if($continent == null){
            return response()->json(["msg" => "Crea un continente para tu fotografía"]);
        }

        Country::find($id)->update([
            "name" => $request->name,
            "continent" => $continent->id
        ]);

        return response()->json(["msg" => "El país se ha actualizado correctamente"]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
