<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attendance;
use App\Schedule;
use App\Logs;
use Carbon\Carbon;
use App\User;

class AttendanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances_for_today = Attendance::whereDate('created_at', Carbon::today())->get();
        return $attendances_for_today;
    }

    public function get_logs()
    {
        $logs =  Logs::latest()->get();
        return $logs;
    }

    public function getClassDetails()
    {
        $details =  Attendance::latest()->first();
        return $details;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $room = $request['room'];
        $user = auth('api')->user();
        $today = Carbon::now()->format('l');

        $check1 = Schedule::where('user_id', $user->id)->whereJsonContains('timeslot', ['room' => $room, 'day' => $today,])->exists();
        $check2 = Attendance::where('user_id', $user->id)->where('room', $room)->whereDate('created_at', Carbon::today())->where('out', null)->exists();

        $schedules = Schedule::where('user_id', $user->id)->whereJsonContains('timeslot', ['room' => $room, 'day' => $today,])->get();

        if ($check2) {
            return response()->json(['type' => 'error', 'message' => 'You have not time-out from your previous class on this room. Please time-out first.']);
        } else if (!$check1) {
            return response()->json(['type' => 'error', 'message' => 'You are not scheduled for this room! Please contact the administrator.']);
        } else {
            foreach ($schedules as $item) {
                $timeslots = $item->timeslot;
                foreach ($timeslots as $sched) {
                    $now = date('H:i');
                    $sched_id = $item->id;
                    $day = $sched['day'];
                    $in = $sched['start_time'];
                    $out = $sched['end_time'];
                    if ($sched['room'] == $room && $now >= $in  && $now < $out  && $today == $day) {
                        $attendance = Attendance::create([
                            'schedule_id' =>  $sched_id,
                            'user_id' =>  $user->id,
                            'room' =>  $room,
                            'schedule' =>  $in . ' - ' . $out,
                            'in' =>  Carbon::now()
                        ]);
                        if ($attendance) {
                            $log = Logs::create([
                                'attendance_id' =>  $attendance->id,
                                'schedule_id' =>  $attendance->schedule_id,
                                'user_id' =>  $user->id,
                                'type' =>  'in',
                                'room' =>  $room
                            ]);
                            $query = User::findOrFail($user->id);
                            $query->update(['status' =>  'on-class']);
                            return $attendance;
                        }
                    }
                    // return response()->json(['type' => 'error', 'message' => 'You are not scheduled for this room! Please contact the administrator.']);
                }
            }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $room = $request['room'];
        // $user = auth('api')->user();
    }

    public function update_timeout(Request $request)
    {
        $room = $request['room'];
        $user_id = auth('api')->user()->id;
        $check = Attendance::where('user_id', $user_id)->where('out', null)->where('room', $room)->whereDate('created_at', Carbon::today())->exists();
        $attendance = Attendance::where('user_id', $user_id)->where('out', null)->whereDate('created_at', Carbon::today())->first();

        if (!$check) {
            return response()->json(['type' => 'error', 'message' => 'You have not timed-in in this room. Please time-in first.']);
        } else {
            $today = Carbon::now()->format('l');
            $schedules = Schedule::where('user_id', $user_id)->whereJsonContains('timeslot', ['room' => $room, 'day' => $today,])->get();

            foreach ($schedules as $item) {
                $timeslots = $item->timeslot;
                foreach ($timeslots as $sched) {
                    $now = date('H:i');
                    $day = $sched['day'];
                    $in = $sched['start_time'];
                    $out = $sched['end_time'];

                    // if ($sched['room'] == $room && $now >= $in  && $now < $out  && $today == $day) {
                    //     return response()->json(['type' => 'error', 'message' => 'You are only allowed to time out at ' . $out . '.']);
                    // }
                    // $attendance = Attendance::findOrFail($details[0]->id)->get();
                    $update = $attendance->update(['out' =>  Carbon::now()]);
                    if ($update) {
                        $log = Logs::create([
                            'attendance_id' =>   $attendance->id,
                            'schedule_id' =>   $attendance->schedule_id,
                            'user_id' =>  $user_id,
                            'type' =>  'out',
                            'room' =>  $room
                        ]);
                        $query = User::findOrFail($user_id);
                        $query->update(['status' =>  'free']);
                        return ['message' => 'Timed out successfuly!'];
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
