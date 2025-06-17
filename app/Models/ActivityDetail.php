<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDetail extends Model
{
    use HasFactory;

    protected $fillable = ['date','location','activity_id','employees'];

   
    
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
   
    public function employeesActivity()
    {
        return $this->hasMany(ActivityEmployee::class, 'activity_id','id');
    }
    public function proofActivity()
    {
        return $this->hasMany(ActivityProof::class, 'activity_id','id');
    }
    public function checkActivity()
    {
        return $this->hasOne(ActivityCheck::class, 'activity_id','id');
    }
}
