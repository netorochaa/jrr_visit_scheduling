<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOsColumnToPeopleTable extends Migration
{
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            $table->string('os', 13)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            // Can't rollback
            // There may be values ​​greater than 10 characters
            // $table->string('os', 10)->nullable()->change();
        });
    }
}
