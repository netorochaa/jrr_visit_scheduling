<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCollectsTable.
 */
class CreateCollectsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collects', function(Blueprint $table) {
			$table->increments('id');
			$table->string('date', 10);
			$table->string('hour', 5);
			$table->char('collectType', 1)->default('1');
			$table->char('status', 1)->default('1');
			$table->char('payment', 1)->default('1');
			$table->string('changePayment', 6)->default('0,00')->nullable();
			$table->string('cep', 9)->nullable();
			$table->string('address', 140);
			$table->string('numberAddress', 14);
			$table->string('complementAddress', 45)->nullable();
			$table->string('referenceAddress', 140);
			$table->string('linkMaps', 254)->nullable();
			$table->char('noFeee', 3)->default('off');
            $table->string('unityCreated', 45)->nullable();
            $table->string('observationCollect', 254)->nullable();
            $table->string('attachment', 254)->nullable();

			// FK
			$table->unsignedInteger('cancelationType_id')->nullable();
			$table->foreign('cancelationType_id')->references('id')->on('cancellationTypes');

            $table->unsignedInteger('collector_id');
			$table->foreign('collector_id')->references('id')->on('collectors');

			$table->unsignedInteger('neighborhood_id');
			$table->foreign('neighborhood_id')->references('id')->on('neighborhoods');

			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');

			$table->timestamps();
			$table->softDeletes();
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
		Schema::dropIfExists('collects');
	}
}
