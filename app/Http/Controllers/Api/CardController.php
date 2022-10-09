<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::all();
        return response()->json($cards, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        //refactorizar sacando validaciones en requests
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'required|image|mimes:jpg,jpgeg,png,jpeg,gif,svg',
            'description' => 'required',
        ]);

        $card = Card::create([
            'title' => $request->title,
            'image' => $request->image,
            'description' => $request->description
        ]);

        $card['image'] = $request->file('image')->store('img', 'public');

        $card->save();

        return response()->json($card, 200);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::find($id);
        return response()->json($card, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $card = Card::find($id);

        $destination = public_path("storage\\" . $card->image);
        $filename = '';

        if ($request->hasFile('image')) {
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $filename = $request->file('image')->store('img', 'public');
        } else {
            $filename = $request->image;
        }

        $card->update([
            'title' => $request->title,
            'image' => $filename,
            'description' => $request->description
        ]);

        return response()->json($card, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::find($id);
        $card->delete();
        $destination = public_path("storage\\" . $card->image);
        File::delete($destination);
    }
}
