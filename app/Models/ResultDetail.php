<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'result_id',
        'unit_id',
        'mark',
        'status',
    ];

    /**
     * Get the result that owns the detail.
     */
    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    /**
     * Get the unit associated with this detail.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}