<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')
            ->latest('date')
            ->paginate(10);

        return Inertia::render('Attendance/Index', [
            'attendances' => $attendances,
        ]);
    }

    public function store(Request $request)
    {
        $employeeId = Employee::where('user_id', Auth::id())->value('id');
        Attendance::checkIn($employeeId);
        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    public function update(Request $request)
    {
        $employeeId = Employee::where('user_id', Auth::id())->value('id');
        Attendance::checkOut($employeeId);
        return redirect()->back()->with('success', 'Checked out successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', 'Attendance record deleted.');
    }
}
