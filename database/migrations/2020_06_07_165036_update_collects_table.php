<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCollectsTable extends Migration
{
    public function up()
    {
        Schema::table('collects', function (Blueprint $table) {
            $table->char('extra')->nullable()->after('attachment');
        });
    }
}
