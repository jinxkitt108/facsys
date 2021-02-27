<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'user_id', 'faculty_id', 'name', 'birthdate', 'email', 'address', 'degree', 'employment_status', 'department','position','username','password','photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
