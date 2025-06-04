<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Employees;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employees::getAllEmployeedWithCompanies(100);
        return response()->json($employees);
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
        return response()->json(Employees::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employees $employees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employees $employees)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employees $employee)
    {
        $employee->update($request->all());
        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employees $employee)
    {
        $result = $employee->delete();
        return response()->json('success');
    }

    public static function getAll()
    {
        $employees = Employees::select('employees.*', 'companies.name as company_name')
            ->leftJoin('companies', 'employees.company_id', '=', 'companies.id')
            ->get();

        return response()->json($employees);
    }

}
