<?php
namespace App\Actions;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Http\Resources\UserResource;

class GetReportData
{
   public function execute($filters)
    {   
        
        $query = Attendance::query()
            ->with('employee'); 

        // Filter by employee
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        // Filter by date range
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $query->whereBetween('date', [$filters['from'], $filters['to']]);
        }

        return $query
            ->orderBy('date', 'desc')
            ->paginate(20)
            ->withQueryString();
    }
}
