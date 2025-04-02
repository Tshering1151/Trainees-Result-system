<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    use HasFactory;

    protected $table = 'trainees';
    protected $primaryKey = 'rim_id'; // Set the primary key to 'rim_id'
    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // Ensure primary key is treated as a string

    protected $fillable = [
        'rim_id',
        'name',
        'course_name',
        'cid',
        'email',
        'contact',
        'emergency_contact',
        'address',
    ];
}

