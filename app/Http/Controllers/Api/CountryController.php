<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Models\Continent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::findCountriesByAuthUser();
        if(count($countries) == 0){
            return response()->json(['msg' => 'No tienes ningún país creado']);
        }
        return response()->json($countries, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'continent' => 'required'
        ]);

        $countrySearched = Country::searchByName($request->name);

        $continent = Continent::searchByName($request->continent);

        if($countrySearched){
            return response()->json(['msg' => 'El país ya existe']);
        }

        if($continent == null){
            return response()->json(['msg' => 'Crea un continente para tu fotografía']);
        }

        $country = Country::create([
            'name' => Str::of($request->name)->title(),
            'continent_id' => $continent->id,
            'user_id' => Auth::user()->id 
        ]);

        $country->save();

        return response()->json([
            'country' => $country,
            'msg' => 'El país se ha creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country = Country::findCountryByAuthUser($id);

        if(!$country){
            return response()->json(['msg' => 'El país no existe en la base de datos']);
        }

        return response()->json($country, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'continent' => 'required'
        ]);

        $countrySearched = Country::searchByName(Str::of($request->name)->title());

        $continent = Continent::searchByName(Str::of($request->continent)->title()); 

        if($countrySearched){
            return response()->json(['msg' => 'El país ya existe en la base de datos']);
        }

        if($continent == null){
            return response()->json(['msg' => 'Crea un continente para tu fotografía']);
        }

        $countryUpdated = Country::findCountryByAuthUser($id);

        if(!$countryUpdated){
            return response()->json(['msg' => 'No tienes un país con ese identificador']);
        }

        $countryUpdated->update([
            'name' => Str::of($request->name)->title(),
            'continent' => $continent->id,
            'user_id' => Auth::user()->id
        ]);

        return response()->json(['msg' => 'El país se ha actualizado correctamente']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::findCountryByAuthUser($id);

        if(!$country){
            return response()->json(['msg' => 'El país no existe en la base de datos']);
        }

        $country->delete();

        return response()->json(['msg' => 'El país se ha eliminado correctamente']);
    }

    public function showByContinent(string $id)
    {
        $countries = Country::findCountriesByContinent($id);

        if(count($countries) == 0){
            return response()->json(['msg' => 'No tienes países en ese continente']);
        }

        return response()->json($countries, 200);
    }
}
