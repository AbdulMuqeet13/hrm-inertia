<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Terminate;
use App\Notifications\Reactivate;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{  
     
    use Notifiable;
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
         $updated = $this->update(['status' => 'terminated']);
        if ($updated) {
            $mailData = [
                "Hi" => "Hi {$this->user->name}",
                "terminated" => "You have been terminated from our team.",
            ];
            $this->user->notify(new Terminate($mailData));
        }
        return $updated;
    }
    public function reactivate(): bool
    {
         $updated = $this->update(['status' => 'active']);
        if ($updated) {
            $mailData = [
                "Hi" => "{$this->user->name}",
                "rehire" => "You have been rehired and welcome back to our team!",
            ];
            $this->user->notify(new Reactivate($mailData));
        }
        return $updated;
    }
    public static function createEmployee(array $data): Employee {
        return Employee::create($data);
    }

    public static function updateEmployee(array $data, Employee $employee): bool {
        return $employee->update($data);
    }
}
