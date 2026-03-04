<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactedLeadList;

class ContactedLeadListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ContactedLeadList::where('user_id', Auth()->id())->get();

        return response()->json([
            'status' => true,
            'message' => 'Contacted Leads Retrieved Successfully',
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

        $contactedlead = ContactedLeadList::create($input);

        if (isset($input['card_image']) && !empty($input['card_image'])) {
            $media = $contactedlead->newAddMedia($input['card_image'])
                ->toMediaCollection(ContactedLeadList::CARD_IMAGE_PATH, config('app.media_disc'));
            $contactedlead->update([
                'card_image' => $media->getFullUrl()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Contacted Lead Added Successfully',
            'data' => $contactedlead
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ContactedLeadList::find($id);

        return response()->json([
            'status' => true,
            'message' => 'Contacted Lead Retrieved Successfully',
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
