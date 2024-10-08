<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_position',
        'company_name',
        'description',
        'company_logo',
        'link',
        'start_date',
        'end_date',
    ];
}
