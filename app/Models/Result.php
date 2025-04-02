<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'rim_id',
        'term',
    ];

    /**
     * Get the course associated with the result.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Get the trainee associated with the result.
     */
    public function trainee()
    {
        return $this->belongsTo(Trainee::class, 'rim_id', 'rim_id');
    }

    /**
     * Get the details for this result.
     */
    public function details()
    {
        return $this->hasMany(ResultDetail::class);
    }

    /**
     * Calculate the overall average mark for this result.
     */
    public function getAverageMarkAttribute()
    {
        $details = $this->details;
        
        if ($details->isEmpty()) {
            return 0;
        }
        
        return round($details->avg('mark'), 2);
    }

    /**
     * Determine if the result is an overall pass.
     */
    public function getIsPassAttribute()
    {
        $details = $this->details;
        
        if ($details->isEmpty()) {
            return false;
        }
        
        // Consider it a pass if all units are passed
        return $details->where('status', 'fail')->count() === 0;
    }
}