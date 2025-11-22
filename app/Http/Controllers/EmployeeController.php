<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Employee;
use App\Notifications\Hired;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;
use App\Notifications\HiredNotification;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;

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

        $mailData = [
        "Hi" => "$user->name",
        
        "hiring" => "Welcome to our company and congratulations on your hiring!",
        ];
        $user->notify(new HiredNotification($mailData));

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
    public function reactivate(Employee $employee)
{
    $employee->reactivate();
    return back()->with('success', 'Employee reactivated.');
}
}
