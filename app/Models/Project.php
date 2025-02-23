<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        "user_id",
        "name",
        "description",
        "start_date",
        "end_date"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
