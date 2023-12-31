<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Companies;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Companies::paginate(100));
    }

    public static function getAll() {
        return response()->json(Companies::all());
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
    public function store(CompanyRequest $request)
    {
        $validator = $request->validated();
        if (is_object($validator)) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        return response()->json(Companies::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Companies $companies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Companies $companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Companies $company)
    {
        $validator = $request->validated();

        if (is_object($validator)) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $company->update($request->all());
        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Companies $company)
    {
        $result = $company->delete();
        return response()->json('success');
    }
}
