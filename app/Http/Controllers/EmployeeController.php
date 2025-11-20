<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\password;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::query()
            ->when(
                $request->search,
                fn($q, $search) => $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Employees/Create', ['roles' => $roles]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::query()->create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => strtolower($request->first_name . $request->last_name) . '@' . strtolower(config('app.name') . '.com'),
            'password' => 'password',
        ]);
        $user->assignRole($request->role);
        // dd($request->all());
        $data = $request->validated();
        $data['user_id'] = $user->id;
        Employee::createEmployee(data: $data);
        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee)
    {       
        $roles = Role::all();
        return Inertia::render('Employees/Edit', [
            'employee' => $employee->load(['user.roles']),
            'roles' => $roles,
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        Employee::updateEmployee($request->validated(), $employee);
        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->terminate();
        return redirect()->back()->with('success', 'Employee terminated.');
    }
}
