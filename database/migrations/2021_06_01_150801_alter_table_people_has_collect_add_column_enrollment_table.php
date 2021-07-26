<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePeopleHasCollectAddColumnEnrollmentTable extends Migration
{
    public function up()
    {
        Schema::table('people_has_collect', function (Blueprint $table) 
        {
            $table->string('enrollment', 30)->nullable()->after('covenant');
        });
    }
}
