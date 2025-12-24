<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;
            $first_name  = $row[0];
            $last_name   = $row[1];
            $department  = $row[2];
            $designation = $row[3];
            $salary      = $row[4];
            $status      = $row[5];
            $role        = strtolower($row[6]);
            if (!$first_name || !$last_name) continue; // skip invalid rows

            // Create User
            $user = User::create([
                'name'     => $first_name . ' ' . $last_name,
                'email'    => strtolower($first_name . $last_name) . '@laravel.com',
                'password' => bcrypt('password'), // default password
            ]);
                if (in_array($role, ['admin', 'hr', 'employee'])) {
                $user->assignRole($role);
                }
            // Create Employee linked to User
            Employee::create([
                'user_id'     => $user->id,
                'first_name'  => $first_name,
                'last_name'   => $last_name,
                'department'  => $department,
                'designation' => $designation,
                'salary'      => $salary,
                'status'      => $status,
                'joined_at'   => now(),
            ]);
        }
    }
}
