<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCollectsTableOldcollectColumn extends Migration
{
    public function up()
    {
        Schema::table('collects', function (Blueprint $table) 
        {
            $table->unsignedInteger('collect_old')->nullable()->after('user_id_cancelled');
            $table->foreign('collect_old')->references('id')->on('collects');
            $table->string('hour_new', 5)->nullable()->after('collect_old');
            $table->unsignedInteger('user_id_modified')->nullable()->after('hour_new');
			$table->foreign('user_id_modified')->references('id')->on('users');
        });
    }
}
