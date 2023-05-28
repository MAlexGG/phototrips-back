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

        $country = Country::searchByName($request->country);
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
