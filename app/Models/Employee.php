<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'department',
        'designation',
        'salary',
        'status',
        'joined_at'
    ];
    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
//    public function department() { return $this->belongsTo(Department::class); }
//    public function designation() { return $this->belongsTo(Designation::class); }
    public function terminate(): bool
    {
        return $this->update(['status' => 'terminated']);
    }
    public function reactivate(): bool
    {
        return $this->update(['status' => 'active']);
    }
    public static function createEmployee(array $data): Employee {
        return Employee::create($data);
    }

    public static function updateEmployee(array $data, Employee $employee): bool {
        return $employee->update($data);
    }
}
