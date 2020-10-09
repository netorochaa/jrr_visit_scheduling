<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCollectorsTable extends Migration
{
    public function up()
    {
        Schema::table('collectors', function (Blueprint $table) {
            $table->datetime('date_start')->nullable()->after('name');
            $table->datetime('date_start_last_modify')->nullable()->after('date_start');
        });
    }
}
