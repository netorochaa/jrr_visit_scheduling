<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRaToOsColumnFromPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->renameColumn('ra', 'os');
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->renameColumn('os', 'ra');
        });
    }
}
