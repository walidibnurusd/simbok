<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name','month','year','num_days','program','service'];

   
    
    public function programs()
    {
        return $this->belongsTo(Program::class, 'program', 'id');
    }
    public function services()
    {
        return $this->belongsTo(Service::class, 'service', 'id');
    }
    public function details()
    {
        return $this->hasMany(ActivityDetail::class);
    }
}
