<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
    ];

    // Relationships
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_specialization');
    }
}
