<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Objective;

class ObjectiveController extends Controller
{
	/**
	 * Get a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(){
	   $objectives = Objective::all();
	   return $objectives;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('objectives');
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
			//validate fields
			$validator = Validator::make($request->objective,
			[
				'name' => 'required|alpha|string',
				'description' => 'required|alpha|string'
			]);
			//if those fields are empty
			if($validator->fails()){
				return response()->json(['error'=>true,'message'=>$validator->errors()->all()]);
			}
			//Cast Objective object
			$objective = (object)$request->objective;
			Objective::create([
				'name' => $objective->name,
				'description' => $objective->description
			]);
			//return message
			return response()->json(["error" => false,"title" =>'success' ,"message" => 'Record registered succesfully!']);
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
			$validator = Validator::make($request->objective,
			[
				'name' => 'required|alpha|string',
				'description' => 'required|alpha|string'
			]);
			//if those fields are empty
			if($validator->fails()){
				return response()->json(['error'=>true,'message'=>$validator->errors()->all()]);
			}
			//Retrieve a objective to update
			$objective = Objective::findOrfail($request->objective['id']);
			$objective->update($request->objective);
			//return message
			return response()->json(["error" => false,"title" =>'success' ,"message" => 'Record succesfully updated!']);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $request
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		if($request->ajax()){
			$objective = Objective::where('id',$request->id);
			$objective->delete();
			return response()->json(["error" => false,"title" =>'success' ,"message" => 'Record succesfully deleted!']);
		}
	}
}
