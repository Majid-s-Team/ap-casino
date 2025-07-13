<?php

namespace App\Http\Controllers;

use App\Models\Casino;
use Illuminate\Http\Request;

class CasinoController extends Controller
{
    public function index()
    {
        return $this->apiResponse(200, 'All Casinos', Casino::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'image' => 'nullable|url'  // Only URL will be saved (no file upload here)
        ]);

        $casino = Casino::create([
            'name' => $request->name,
            'location' => $request->location,
            'image' => $request->image
        ]);

        return $this->apiResponse(201, 'Casino Created', $casino);
    }

    public function show($id)
    {
        $casino = Casino::find($id);
        return $casino
            ? $this->apiResponse(200, 'Casino Details', $casino)
            : $this->apiResponse(404, 'Casino Not Found');
    }

    public function update(Request $request, $id)
    {
        $casino = Casino::find($id);
        if (!$casino) {
            return $this->apiResponse(404, 'Casino Not Found');
        }

        $request->validate([
            'name' => 'nullable|string',
            'location' => 'nullable|string',
            'image' => 'nullable|url'  // Only URL will be updated
        ]);

        $casino->update($request->only('name', 'location', 'image'));

        return $this->apiResponse(200, 'Casino Updated', $casino);
    }

    public function destroy($id)
    {
        $casino = Casino::find($id);
        if (!$casino) {
            return $this->apiResponse(404, 'Casino Not Found');
        }

        $casino->delete();

        return $this->apiResponse(200, 'Casino Deleted');
    }
}
