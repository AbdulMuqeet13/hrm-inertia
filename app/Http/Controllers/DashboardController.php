<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('employee')) {
            $employee = Employee::where('user_id', $user->id)->first();
            $attendance = Attendance::where('employee_id', $employee->id)->latest()->first();

            return Inertia::render('Dashboard/Index', [
                'employee' => $employee,
                'attendance' => $attendance,
            ]);
        }

        $today = Carbon::today();
        $stats = [
            'total_employees' => Employee::count(),
            'present_today'   => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'on_leave'        => Attendance::whereDate('date', $today)->where('status', 'leave')->count(),
        ];
        if ($user->hasRole('admin')) {
            $stats['terminated'] = Employee::where('status', 'terminated')->count();
        }

        $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'monthly' => $monthlyStats,
        ]);
    }
}
