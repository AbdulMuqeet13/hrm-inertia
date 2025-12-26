<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\Attendance;
use App\Actions\GetHrDashboard;
use App\Actions\GetUserDashboard;
use App\Actions\GetAdminDashboard;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // dd($user->getRoles);
//         $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
//            ->groupBy('month')
//            ->orderBy('month')
//            ->get();
        if ($user->hasRole('employee')) {
            $employeeId = $user->employee ? $user->employee->id : null;

            // If no employee or no attendance yet, return empty collection
            $monthlyStats = $employeeId
                ? Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
                    ->where('employee_id', $employeeId)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                : collect(); // empty collection
            $data = GetUserDashboard::get($user);
            $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
    } elseif ($user->hasRole('admin')) {
            $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $data = GetAdminDashboard::get($user);
            $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
            // $stats['terminated'] = Employee::where('status', 'terminated')->count();
        } elseif ($user->hasRole('hr')) {
            $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $data = GetHrDashboard::get($user);
            $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
            // $stats['terminated'] = Employee::where('status', 'terminated')->count();
        }

    }

}
