<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPatient extends Model
{
    use HasFactory;

    // Define the table name if it's different from the model name
    protected $table = 'activity_patients';

    protected $fillable = [
        'activity_proof_id',
        'patient_id',
        'description',
     
    ];
     public function patient()
    {
        return $this->belongsTo(Patients::class, 'patient_id','id');
    }
    public function activityProof()
    {
        return $this->belongsTo(ActivityProof::class, 'activity_proof_id','id');
    }

      
}
