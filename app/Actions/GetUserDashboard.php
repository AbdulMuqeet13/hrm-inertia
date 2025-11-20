<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Http\Resources\UserResource;

class GetUserDashboard
{
    public static function get($user): array
    {     
         $today = Carbon::today();
         $employee = Employee::where('user_id', $user->id)->first();
         $attendance = Attendance::where('employee_id', $employee->id)->latest()->first();
         $total_employees = Employee::count();
         $present_today   = Attendance::whereDate('date', $today)->where('status', 'present')->count();
         $on_leave        = Attendance::whereDate('date', $today)->where('status', 'leave')->count();

        return [
            'role' => 'employee',
            'stats' => [
                'employee_data' => $employee,
                'attendence_data' =>  $attendance,
                'total_employees' => $total_employees,
                'present_today'   => $present_today,
                'on_leave'        => $on_leave ,
            ],
        ];
    }
}
