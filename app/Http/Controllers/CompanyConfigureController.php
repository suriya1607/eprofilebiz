<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyConfigure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class CompanyConfigureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                $config = CompanyConfigure::where('user_id',Auth::id())->first();

        return view('company-config.index', compact('config'));
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
        
        // $request->validate([
        //     'name'        => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'logo'        => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        //     'social_links.facebook' => 'nullable|url',
        //     'social_links.linkedin' => 'nullable|url',
        //     'social_links.twitter'  => 'nullable|url',
        // ]);

        $data = $request->only(['name', 'description']);
        $data['user_id'] = Auth::id();
        $data['social_links'] = $request->input('social_links', []);

        // Get first record or create
    $config = CompanyConfigure::firstOrNew(['user_id' => Auth::id()]);
        // File upload
    if ($request->hasFile('logo')) {
    $userId = auth()->id(); // current user id
    // Delete old file if exists
        if (!empty($config->logo) && file_exists(public_path($config->logo))) {
            unlink(public_path($config->logo));
        }
    $path = $request->file('logo')->move(
        public_path("companylogo/{$userId}"),
        time().'_'.$request->file('logo')->getClientOriginalName()
    );

    // Save relative path in DB
    $data['logo'] = "companylogo/{$userId}/".basename($path);
    }

        $config->fill($data);
        $config->save();
         Flash::success(__('Company configuration saved successfully.'));
        return Redirect::back();
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
