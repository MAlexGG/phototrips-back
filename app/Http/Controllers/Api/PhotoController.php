<?php

namespace App\Http\Controllers\Api;

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
        $photos = Photo::all();
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
            "image" => "required|max:500"
        ]);

        $user = Auth::user();
        $photo = Photo::create([
            "name" => $request->name,
            "description" => $request->description,
            "image" => $request->image,
            "user_id" => $user->id
        ]);

        $photo->save();
        return response()->json($photo, 201, [
            "msg" => "Photo has been created successfully"
        ]);
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
