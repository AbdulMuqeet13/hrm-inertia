<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Transaction;
use App\Http\Resources\UserResource;

class GetHrDashboard
{
    public static function get( $user): array
    {
       $today = Carbon::today();
         $total_employees = Employee::count();
         $present_today   = Attendance::whereDate('date', $today)->where('status', 'present')->count();
         $on_leave        = Attendance::whereDate('date', $today)->where('status', 'leave')->count();
         $terminated      = Employee::where('status', 'terminated')->count();
        return [
            'role' => 'hr',
            'stats' => [
                'total_employees' => $total_employees,
                'present_today'   => $present_today,
                'on_leave'        => $on_leave ,
                'terminated'      => $terminated,
            ],
        ];
    }
}
