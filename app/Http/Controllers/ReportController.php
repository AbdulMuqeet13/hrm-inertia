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
        $filters = $request->all();
        if (auth()->user()->hasRole('admin')) {
            // If the user is an admin, show all users
            $users = User::all();
        } 
            if (auth()->user()->hasRole('hr')) {
            // If the user is an admin, show all users
            $users = User::all();
        }
          if (auth()->user()->hasRole('employee')) {
            $filters["employee_id"]= Auth::id();
            $users = User::where('id', auth()->user()->id)->get();
        }
        $reportData = (new GetReportData())->execute($filters);
            // dd($reportData->toArray());
        return Inertia::render('report/Index', [
            'reportData' => $reportData,
            'filters' => $filters,
            'users' => $users,

        ]);
  
}
}




        