<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with('role');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role_id') && $request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        // Stats
        $stats = [
            'count' => Employee::count(),
            'total_salary' => Employee::sum('salary'),
            'total_due' => Employee::sum('due'),
        ];

        $employees = $query->latest()->paginate($request->per_page ?? 10);

        return response()->json([
            'employees' => $employees,
            'stats' => $stats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'mobile' => 'required|string|unique:employees,mobile',
            'password' => 'required|string|min:6',
            'nid' => 'required|string',
            'gender' => 'required|string|in:Male,Female,Other',
            'salary_type' => 'required|string',
            'status' => 'boolean',
            
            // Optional fields
            'father_name' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'due' => 'nullable|numeric',
            'salary_deduction' => 'nullable|numeric',
            'division' => 'nullable|string',
            'district' => 'nullable|string',
            'thana' => 'nullable|string',
            'address' => 'nullable|string',
            'reference_name' => 'nullable|string',
            'reference_address' => 'nullable|string',
            'reference_mobile' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $employee = Employee::create($validated);

        return response()->json([
            'message' => 'Employee created successfully',
            'data' => $employee
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return response()->json($employee->load('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'mobile' => ['required', 'string', Rule::unique('employees')->ignore($employee->id)],
            'password' => 'nullable|string|min:6',
            'nid' => 'required|string',
            'gender' => 'required|string|in:Male,Female,Other',
            'salary_type' => 'required|string',
            'status' => 'boolean',
            
            // Optional fields
            'father_name' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'due' => 'nullable|numeric',
            'salary_deduction' => 'nullable|numeric',
            'division' => 'nullable|string',
            'district' => 'nullable|string',
            'thana' => 'nullable|string',
            'address' => 'nullable|string',
            'reference_name' => 'nullable|string',
            'reference_address' => 'nullable|string',
            'reference_mobile' => 'nullable|string',
        ]);

        if (isset($validated['password']) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => $employee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
