<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // Set the table name explicitly if not already default
    protected $table = 'units';

    // Set the fillable fields for mass assignment
    protected $fillable = [
        'course_id', 
        'term', 
        'unit_name', 
        'lecture', 
        'description'
    ];

    // Ensure that 'course_id' is treated as a string
    protected $casts = [
        'course_id' => 'string',  // Cast course_id to string
    ];

    // Define the inverse relationship with the Course model
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}


