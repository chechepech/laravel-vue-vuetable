<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseObjective extends Model
{
    protected $guarded = [];

    public function objective(){
       return $this->hasOne('App\Objective','id','objective_id');
    }

    public function otherObjective(){
        return $this->hasOne('App\OtherObjective','id','other_objective_id');
    }
}
