<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function Objectives(){
     	return $this->hasMany('App\CourseObjective','course_id','id');
    }
}
