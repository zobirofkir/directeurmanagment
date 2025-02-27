<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'hiring_date',
        'career_path',
        'contract_type',
        'degree',
        'phone',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
