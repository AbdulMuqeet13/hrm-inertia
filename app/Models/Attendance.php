<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function checkIn(int $employeeId) {
        $date = now()->toDateString();
        return Attendance::updateOrCreate(
            ['employee_id' => $employeeId, 'date' => $date],
            ['check_in' => now()->toTimeString(), 'status' => 'present']
        );
    }
    public static function checkOut(int $employeeId) {
        $attendance = Attendance::whereDate('date', today())->where('employee_id', $employeeId)->first();
        if ($attendance) $attendance->update(['check_out' => now()->toTimeString()]);
        return $attendance;
    }
}
