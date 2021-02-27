<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id =  auth('api')->user()->id;
        $schedule = Schedule::where('user_id', $id)->latest()->get();
        return $schedule;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $schedule = Schedule::create([
            'user_id'=>$request['user_id'],
            'subject_code' => $request['subject_code'],
            'description' => $request['description'],
            'course' => $request['course'],
            'year' => $request['year'],
            'section' => $request['section'],
            'timeslot' => $request['timeslot'],
            'semester' => $request['semester'],
            'school_year' => $request['school_year']
        ]);

        return $schedule;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = Schedule::where('user_id', $id)->get();
        foreach($schedule as $item) {
            if($item['course'] == 'Bachelor of Science in Information Technology') {
                $item['course'] = 'BSIT';
            }
            if($item['year'] == 'First Year'){
                $item['year'] = '1';
            }
            if($item['year'] == 'Second Year'){
                $item['year'] = '2';
            }
            if($item['year'] == 'Third Year'){
                $item['year'] = '3';
            }
            if($item['year'] == 'Fourth Year'){
                $item['year'] = '4';
            }
            if($item['section'] == 'Section A'){
                $item['section'] = 'A';
            }
            if($item['section'] == 'Section B'){
                $item['section'] = 'B';
            }
            if($item['section'] == 'Section C'){
                $item['section'] = 'C';
            }
            if($item['section'] == 'Section D'){
                $item['section'] = 'D';
            }
            if($item['section'] == 'Section E'){
                $item['section'] = 'E';
            }
            if($item['section'] == 'Section F'){
                $item['section'] = 'F';
            }
        }

        return $schedule;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule:: findOrFail($id);
        $schedule->update($request->all());
        return ['message' => 'Updating'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('id',$id);

        //delete faculty
        $schedule -> delete();

        return ['message' => 'User Deleted'];
    }
}
