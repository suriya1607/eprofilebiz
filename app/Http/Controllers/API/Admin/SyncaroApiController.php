<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardList;

class SyncaroApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;

        // if ($request->hasFile('card_image')) {
        //     $imagePath = $request->file('card_image')
        //                         ->store('cards', 'public');
        // }

        $card = CardList::create([
            'id' => $request->id,
            'address' => $request->address,
            'card_image' => $imagePath,
            'email' => $request->email,
            'name' => $request->name,
            'organization' => $request->organization,
            'phone' => $request->phone,
            'qr_code' => $request->qr_code,
            'scannedLocation' => $request->scannedLocation,
            'scannedLocationGeoPoint' => $request->scannedLocationGeoPoint,
            'service' => $request->service,
            'tag' => $request->tag,
            'title' => $request->title,
            'url' => $request->url,
            'user_id' => Auth()->id(),
            'favourite' => $request->favourite ?? false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Card Created Successfully',
            'data' => $card
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
