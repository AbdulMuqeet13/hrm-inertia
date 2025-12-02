<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Actions\GetReportData;
use Illuminate\Support\Facades\Auth;
use Illuminate\JsonSchema\Types\Type;

class ReportController extends Controller
{
    public function index(Request $request)
    {
    // Return the Inertia view with the users data
    return Inertia::render('report/Index' );
    }

    public function show(Request $request, $type){
        // dd($type);
       
     if ($type === 'user-attendance') {

        if (auth()->user()->hasRole('admin')) {
            // If the user is an admin, show all users
            $users = User::all();
        } 
            if (auth()->user()->hasRole('hr')) {
            // If the user is an admin, show all users
            $users = User::all();
        }
            if (auth()->user()->hasRole('employee')) {
            $users = User::where('id', auth()->user()->id)->get();
        }
        $reportData = (new GetReportData())->execute($request->all());
            dd($reportData->toArray());
        return Inertia::render('report/Userattendance', [
            'reportData' => $reportData,
            'users' => $users,

        ]);

    }
    if($type === 'over-all-attendance'){

        if (auth()->user()->hasRole('admin')) {
        // If the user is an admin, show all users
        $users = User::all();
    } 
        if (auth()->user()->hasRole('hr')) {
        // If the user is an admin, show all users
        $users = User::all();
    }
        if (auth()->user()->hasRole('employee')) {
        $users = User::where('id', auth()->user()->id)->get();
    }

    $reportData = (new GetReportData())->execute($request->all());
        dd($reportData->toArray());
            // Render the overall attendance report with the fetched data
        return Inertia::render('report/OverallAttendanceReport', [
            'reportData' => $reportData,
            'users' => $users,
        ]);
    }

}
}




        