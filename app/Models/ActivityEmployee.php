<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityEmployee extends Model
{
    use HasFactory;

    // Define the table name if it's different from the model name
    protected $table = 'activity_employees';

    protected $fillable = [
        'activity_id',
        'employee_id',
    ];

    // Relasi dengan model ActivityDetail
    public function activity()
    {
        return $this->belongsTo(ActivityDetail::class, 'activity_id','id');
    }

    // Relasi dengan model User (karyawan)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id','id');
    }
    
}
