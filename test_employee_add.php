<?php

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Requires: php artisan tinker
// Run this code inside tinker or a temp route

$role = Role::firstOrCreate(
    ['slug' => 'manager'],
    ['name' => 'Manager']
);

$data = [
    'name' => 'Browser Test User',
    'role_id' => $role->id,
    'mobile' => '01999888777',
    'password' => 'password123',
    'nid' => '9988776655',
    'gender' => 'Female',
    'salary_type' => 'Monthly',
    'status' => true,
    // Optional
    'father_name' => 'Father',
    'salary' => 5000,
    'due' => 0,
    'salary_deduction' => 0,
    'division' => 'Dhaka',
    'district' => 'Dhaka',
    'thana' => 'Gulshan',
    'address' => '123 Street',
    'reference_name' => 'Ref Name',
    'reference_address' => 'Ref Addr',
    'reference_mobile' => '01811111111',
];

$request = Illuminate\Http\Request::create('/api/config/employees', 'POST', $data);
$controller = new App\Http\Controllers\Api\Employee\EmployeeController();

try {
    $response = $controller->store($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content: " . $response->getContent() . "\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
