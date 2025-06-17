<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityProof extends Model
{
    use HasFactory;

    // Define the table name if it's different from the model name
    protected $table = 'activity_proofs';

    protected $fillable = [
        'activity_id',
        'value',
        'advice',
        'image',
    ];

    // Relasi dengan model ActivityDetail
    public function activity()
    {
        return $this->belongsTo(ActivityDetail::class, 'activity_id','id');
    }
    public function patients()
    {
        return $this->hasMany(ActivityPatient::class, 'activity_proof_id','id');
    }
    public function getPatientCountAttribute()
    {
        return $this->patients()->count();
    }
      
}
