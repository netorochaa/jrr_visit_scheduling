<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectsCanceledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collects_canceled', function (Blueprint $table) {
            $table->increments('id');
            $table->char('idCollect');
			$table->datetime('date');
			$table->string('hour', 5);
			$table->char('collectType', 1)->default('1');
			$table->char('status', 1)->default('1');
			$table->char('payment', 1)->default('1');
			$table->string('changePayment', 6)->default('0.00')->nullable();
			$table->string('cep', 9)->nullable();
			$table->string('address', 140)->nullable();
			$table->string('numberAddress', 14)->nullable();
			$table->string('complementAddress', 45)->nullable();
			$table->string('referenceAddress', 140)->nullable();
			$table->string('linkMaps', 254)->nullable();
			$table->string('AuthCourtesy', 140)->nullable();
            $table->string('unityCreated', 45)->nullable();
            $table->string('observationCollect', 254)->nullable();
            $table->string('attachment', 254)->nullable();

			// FK
			$table->unsignedInteger('cancellationType_id')->nullable();
			$table->foreign('cancellationType_id')->references('id')->on('cancellationTypes');

            $table->unsignedInteger('collector_id');
			$table->foreign('collector_id')->references('id')->on('collectors');

			$table->unsignedInteger('neighborhood_id')->nullable();
			$table->foreign('neighborhood_id')->references('id')->on('neighborhoods');

			$table->unsignedInteger('user_id')->nullable();
			$table->foreign('user_id')->references('id')->on('users');

			$table->datetime('reserved_at')->nullable();
			$table->datetime('confirmed_at')->nullable();
			$table->datetime('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('collects_canceled');
    }
}
