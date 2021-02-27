<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendanceIdToLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->integer('attendance_id')->after('id')->unsigned();
            $table->integer('schedule_id')->before('user_id')->unsigned();

            $table->foreign('attendance_id')
                ->references('id')->on('attendances')
                ->onDelete('cascade');
            $table->foreign('schedule_id')
                ->references('id')->on('schedules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
        });
    }
}
