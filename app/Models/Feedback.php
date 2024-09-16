<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_image',
        'guest_name',
        'project',
        'message',
        'status',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
