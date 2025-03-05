<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'category',
        'archived',
        'date'
    ];

    protected $casts = [
        'archived' => 'boolean',
        'date' => 'datetime',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add a scope for active (non-archived) documents
    public static function activeCount()
    {
        return static::where('archived', false)->count();
    }
}
