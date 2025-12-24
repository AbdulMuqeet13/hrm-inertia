<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportDetails implements ShouldQueue
{
    use Queueable;
    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
           $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = Storage::path($this->filePath);
        Excel::import(new EmployeeImport,$file);
        Storage::delete($this->filePath);
        }
}
