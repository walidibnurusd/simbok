<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdviceActivityProof extends Model
{
    use HasFactory;

    // Define the table name if it's different from the model name
    protected $table = 'advice_activity_proofs';

    protected $fillable = [
        'activity_proof_id',
        'advice',
        'user_id',
    ];

  
      
}
