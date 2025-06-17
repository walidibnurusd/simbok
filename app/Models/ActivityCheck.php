<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCheck extends Model
{
    use HasFactory;

    protected $fillable = ['photo','letter_assign','activity_id','document'];

   
    
    public function activity()
    {
        return $this->belongsTo(ActivityDetail::class, 'activity_id', 'id');
    }

}
