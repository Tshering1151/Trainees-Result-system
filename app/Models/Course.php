<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id'; // Set primary key as 'course_id'
    public $incrementing = false; // Prevent auto-incrementing (manual entry)
    protected $keyType = 'string'; // Define primary key type as string

    protected $fillable = [
        'course_id',   // Manually entered Course ID
        'course_name',
        'start_year',  // Start year (YYYY)
        'end_year',    // End year (YYYY)
        'duration',    // Duration in months
        'total_term',
        'description'
    ];

     // Define the relationship with the Unit model
     public function units()
     {
         return $this->hasMany(Unit::class, 'course_id', 'course_id');
     }
}
