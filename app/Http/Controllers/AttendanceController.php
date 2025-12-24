<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
           if(!$employeeId){
            return redirect()->back()->with('success', 'this user has no id against employee id.');
        }
        $attendance=  Attendance::checkIn($employeeId);
        $attendance->save();
        return redirect()->back()->with('success', 'Checked in successfully.');
        
    }

    public function update(Request $request)
    {
        $employeeId = Employee::where('user_id', Auth::id())->value('id');
        $attendance = Attendance::checkOut($employeeId);
        $attendance->save();
        return redirect()->back()->with('success', 'Checked out successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', 'Attendance record deleted.');
    }
    public function export_attendance(){
        //   dd('Export started!');
         return Excel::download(new AttendanceExport, 'attendance.xlsx');
    }
}
