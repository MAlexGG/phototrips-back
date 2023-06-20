<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::findCitiesByAuthUser();
        if(count($cities) == 0){
            return response()->json(['msg' => 'No tienes ninguna ciudad creada']);
        }
        return response()->json($cities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'country' => 'required'
        ]);

        $citySearched = City::searchByName($request->name);

        $country = Country::searchByName($request->country);
        
        if($citySearched){
            return response()->json(['msg' => 'La ciudad ya existe']);
        }

        if($country == null){
            return response()->json(['msg' => 'Crea un país para tu fotografía']);
        }

        $city = City::create([
            'name' => Str::of($request->name)->title(),
            'country_id' => $country->id,
            'user_id' => Auth::user()->id 
        ]);

        $city->save();

        return response()->json([
            'city' => $city,
            'msg' => 'La ciudad se ha creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $city = City::findCityByAuthUser($id);

        if(!$city){
            return response()->json(['msg' => 'La ciudad no existe en la base de datos']);
        }

        return response()->json($city, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'country' => 'required'
        ]);

        $cityUpdated = City::findCityByAuthUser($id);
        if(!$cityUpdated){
            return response()->json(['msg' => 'No tienes una ciudad con ese identificador']);
        }

        $citySearched = City::searchByName(Str::of($request->name)->title());
        if($citySearched){
            return response()->json(['msg' => 'La ciudad ya existe en la base de datos']);
        }

        $country = Country::searchByName($request->country);
        if($country == null){
            return response()->json(['msg' => 'Crea un país para tu fotografía']);
        }

        $cityUpdated->update([
            'name' => Str::of($request->name)->title(),
            'country_id' => $country->id,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'msg' => 'La ciudad se ha actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $city = City::findCityByAuthUser($id);

        if(!$city){
            return response()->json(['msg' => 'La ciudad no existe en la base de datos']);
        }

        $city->delete();

        return response()->json(['msg' => 'La ciudad se ha eliminado correctamente']);
    }

    public function showByCountry(string $id)
    {
        $cities = City::findCitiesByCountry($id);

        if(count($cities) == 0){
            return response()->json(['msg' => 'No tienes ciudades en ese país']);
        }

        return response()->json($cities, 200);
    }
}
