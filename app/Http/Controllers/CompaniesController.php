<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    public function index()
    {
        return response()->json(Companies::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'website']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = '/storage/' . $path; // e.g., /storage/logos/filename.jpg
        }

        $Companies = Companies::create($data);

        return response()->json($Companies, 201);
    }

    public function update(Request $request, $id)
    {
        $Companies = Companies::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'website']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($Companies->logo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $Companies->logo));
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $Companies->update($data);

        return response()->json($Companies);
    }

    public function destroy($id)
    {
        $Companies = Companies::findOrFail($id);
        if ($Companies->logo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $Companies->logo));
        }
        $Companies->delete();
        return response()->json(['message' => 'Companies deleted']);
    }

    public static function getAll() { return response()->json(Companies::all()); }
}
