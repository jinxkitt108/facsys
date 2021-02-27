<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['faculty' => 'API\FacultyController']);
Route::get('status', 'API\FacultyController@getStatus');
Route::get('profile', 'API\FacultyController@getProfile');
Route::apiResources(['schedule' => 'API\ScheduleController']);

Route::apiResources(['room' => 'API\RoomController']);

Route::apiResources(['attendance' => 'API\AttendanceController']);
Route::put('attendance', 'API\AttendanceController@update_timeout');
Route::get('class_details', 'API\AttendanceController@getClassDetails');
//Logs
Route::get('logs', 'API\AttendanceController@get_logs');
