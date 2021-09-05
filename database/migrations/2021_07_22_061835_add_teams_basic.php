<?php

use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTeamsBasic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $team = new Team();
        $team->id = 1;
        $team->user_id = 1;
        $team->name = '미인증 사용자';
        $team->personal_team = 0;
        $team->save();

        $team2 = new Team();
        $team2->id = 2;
        $team2->user_id = 1;
        $team2->name = 'WDJ';
        $team2->personal_team = 0;
        $team2->save();
     
        $team3 = new Team();
        $team3->id = 3;
        $team3->user_id = 1;
        $team3->name = 'CPJ';
        $team3->personal_team = 0;
        $team3->save();

        $team4 = new Team();
        $team4->id = 4;
        $team4->user_id = 1;
        $team4->name = '교수';
        $team4->personal_team = 0;
        $team4->save();

        $team5 = new Team();
        $team5->id = 5;
        $team5->user_id = 1;
        $team5->name = '반대표';
        $team5->personal_team = 0;
        $team5->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        // Schema::table('teams', function (Blueprint $table) {
        //     $team = Team::all();
        //     $team->delete();
        // });
    }
}
