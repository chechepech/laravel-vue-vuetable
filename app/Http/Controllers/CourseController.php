<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Course;
use App\Objective;
use App\OtherObjective;
use App\CourseObjective;

class CourseController extends Controller
{

    public function get()
    {
        $courses = [];
        $course = Course::with('Objectives')->get();
        //iterate each course
        foreach ($course as $key => $value) {
            //iterate each objectives into course
            foreach ($value->objectives as $key => $value2) {
                if($value2->objective_id != NULL && $value2->objective_id != ''){
                    $objective = Objective::find($value2->objective_id);
                    //Virtual field index -> Objective
                    $objective->index = $value2->objective_id.',Objective';
                    $value2->objective = $objective;
                }
                if($value2->other_objective_id != NULL && $value2->other_objective_id != ''){
                    $otherObjective = OtherObjective::find($value2->other_objective_id);
                     //Virtual field index -> otherObjective
                    $otherObjective->index = $value2->other_objective_id.',otherObjective';
                    $value2->objective = $otherObjective;
                }
            }
            array_push($courses, $value);
        }
        return $courses;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return view('courses');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){
            //regex:/^[a-zA-Z\s]+$/u'
            $validator = Validator::make($request->course,[
                'name' => 'required|alpha',
                'description' => 'required|alpha'
            ]);
            //if validation fails
            if($validator->fails()){
               return response()->json(['error'=>true,'message'=>$validator->errors()->all()]);
            }
            //cast course object
            $course = (object)$request->course;
            //Save Course and retrieve id course
            $idCourse = Course::create([
                 'name' => $course->name,
                 'description' => $course->description
            ])->id;
            //iteration objectives
            foreach ($course->objectives as $key => $value) {
                $array = explode(',',$value['index']);
                //var_dump($array);
                if($array[0] == -1){
                    //Create a new objective other
                    $other_Objective = new OtherObjective();
                    $other_Objective->name = $value['name'];
                    $other_Objective->description = $value['description'];
                    $other_Objective->save();
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $idCourse;
                    $rel->other_objective_id = $other_Objective->id;
                    $rel->save();
                } else if ($array[1] == 'otherObjective'){
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $idCourse;
                    $rel->other_objective_id = $array[0];
                    $rel->save();
                } else {
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $idCourse;
                    $rel->objective_id = $array[0];
                    $rel->save();
                }
            }

            return response()->json(["error" => false,"title" =>'success' ,"message" => 'Course registered succesfully!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->ajax()){
            //validate fields
            $validator = Validator::make($request->course,
            [
                'name' => 'required|alpha|string',
                'description' => 'required|alpha|string'
            ]);
            //if those fields are empty
            if($validator->fails()){
                return response()->json(['error'=>true,'message'=>$validator->errors()->all()]);
            }
            //Retrieve a objective to update
            $course = Course::findOrfail($request->course['id']);
            $course->name = $request->course['name'];
            $course->description = $request->course['description'];
            $course->save();
            //Delete all objectives
            CourseObjective::where('course_id',$course->id)->delete();
            //cast Course in Object
            $objectives = (object)$request->course['objectives'];
            //iterate objectives of the course and save
            foreach ($objectives as $key => $value) {
                $value = (Object)$value;
                $array = explode(',',$value->objective['index']);
                if($array[0] == -1){
                    //Create a new objective other
                    $other_Objective = new OtherObjective();
                    $other_Objective->name = $value->objective['name'];
                    $other_Objective->description = $value->objective['description'];
                    $other_Objective->save();
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $course->id;
                    $rel->other_objective_id = $other_Objective->id;
                    $rel->save();
                } else if ($array[1] == 'otherObjective'){
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $course->id;
                    $rel->other_objective_id = $array[0];
                    $rel->save();
                } else {
                    //save records on relation table
                    $rel = new CourseObjective();
                    $rel->course_id = $course->id;
                    $rel->objective_id = $array[0];
                    $rel->save();
                }
            }
            //return message
            return response()->json(["error" => false,"title" =>'success' ,"message" => 'Course succesfully updated!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if($request->ajax()){
            $course = Course::where('id',$request->id);
            $course->delete();
            return response()->json(["error" => false,"title" =>'success' ,"message" => 'Course succesfully deleted!']);
        }
    }
}
