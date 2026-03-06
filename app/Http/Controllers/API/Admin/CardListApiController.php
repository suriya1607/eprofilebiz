<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardList;

class CardListApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CardList::where('user_id', Auth()->id())->get();

        return response()->json([
            'status' => true,
            'message' => 'Cards Retrieved Successfully',
            'data' => $data
        ]);
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

        $input = $request->all();
        $input['user_id'] = auth()->id();

        $card = CardList::create($input);

        if (isset($input['card_image']) && !empty($input['card_image'])) {

            $media = $card->newAddMedia($input['card_image'])
                ->toMediaCollection(CardList::CARD_IMAGE_PATH, config('app.media_disc'));

            $card->update([
                'card_image' => $media->getFullUrl()
            ]);
        }

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
        $data = CardList::find($id);

        return response()->json([
            'status' => true,
            'message' => 'Card Retrieved Successfully',
            'data' => $data
        ]);
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
