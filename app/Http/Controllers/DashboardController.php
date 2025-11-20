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
         $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        if ($user->hasRole('employee')) {
            $data = GetUserDashboard::get($user);
             $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
        }

        // $today = Carbon::today();
        // $stats = [
        //     'total_employees' => Employee::count(),
        //     'present_today'   => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
        //     'on_leave'        => Attendance::whereDate('date', $today)->where('status', 'leave')->count(),
        // ];
        elseif ($user->hasRole('admin')) {
            $data = GetAdminDashboard::get($user);
             $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
            // $stats['terminated'] = Employee::where('status', 'terminated')->count();
        }
        elseif ($user->hasRole('hr')) {
            $data = GetHrDashboard::get($user);
             $data['monthly'] = $monthlyStats;
            return Inertia::render('Dashboard/Index', $data);
            // $stats['terminated'] = Employee::where('status', 'terminated')->count();
        }
        $monthlyStats = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Dashboard/Index', [

            'monthly' => $monthlyStats,
        ]);
    }
}
