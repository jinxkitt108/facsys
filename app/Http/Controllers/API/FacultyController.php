<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Faculty;
use App\User;

class FacultyController extends Controller
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
        $allfaculties = Faculty::paginate(10);

        return $allfaculties;
    }

    public function getStatus()
    {
        $status = auth('api')->user()->status;

        return $status;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::create([
            'name' =>  $request['name'],
            'type' =>  'Faculty',
            'email' =>  $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        if ($user) {
            $photo = $request['photo'];

            $filename = uniqid() . '.' . explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];
            \Image::make($photo)->save(public_path('img/profile_photos/') . $filename);

            $faculty = Faculty::create([
                'user_id' => $user->id,
                'faculty_id' => $request['faculty_id'],
                'name' => $request['name'],
                'birthdate' => $request['birthdate'],
                'email' => $request['email'],
                'address' => $request['address'],
                'degree' => $request['degree'],
                'employment_status' => $request['employment_status'],
                'department' => $request['department'],
                'position' => $request['position'],
                'username' => $request['username'],
                'password' => $request['password'],
                'photo' => $filename
            ]);
        }


        return $faculty;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = Faculty::where('user_id', $id)->get();

        return $faculty;
    }


    public function getProfile()
    {
        $user = auth('api')->user();
        $user->faculty_profile;

        return $user;
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

        if (strlen($request->photo) > 100) {
            $photo = $request->photo;
            $filename = uniqid() . '.' . explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];
            \Image::make($photo)->save(public_path('img/profile_photos/') . $filename);
            $request->merge(['photo' => $filename]);
        }

        $faculty = Faculty::where('user_id', $id);
        $user = User::findOrFail($id);

        $new = Hash::make($request->password);
        $faculty->update($request->all());
        $user->update($request->all());
        $user->update(['password' => $new]);
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

        $user = User::findOrFail($id);
        $faculty = Faculty::where('user_id', $id);

        //delete faculty
        $faculty->delete();
        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
