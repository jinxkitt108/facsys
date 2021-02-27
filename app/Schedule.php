<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable =[
        'user_id','subject_code','description','course','year','section', 'timeslot', 'semester','school_year'
    ];

    protected $casts = [
        'timeslot' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
