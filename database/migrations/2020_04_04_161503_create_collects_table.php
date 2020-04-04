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
			$table->char('collect-type', 1)->default('1');
			$table->char('status', 1)->default('1');
			$table->char('payment', 1)->default('1');
			$table->string('change-payment', 6)->default('0,00')->nullable();
			$table->string('cep', 9)->nullable();
			$table->string('address', 140);
			$table->string('number-address', 14);
			$table->string('complement-address', 45)->nullable();
			$table->string('reference-address', 140);
			$table->string('link-maps', 254)->nullable();
			$table->char('urgency', 1)->default('1');
			$table->string('observation', 350)->nullable();
			$table->string('unity-created', 45)->nullable();
			$table->string('attachment', 45)->nullable();
			
			// FK
			$table->unsignedInteger('cancelation-type_id')->nullable();
			$table->foreign('cancelation-type_id')->references('id')->on('cancellation-types');
			$table->unsignedInteger('patient-type_id');
			$table->foreign('patient-type_id')->references('id')->on('patient-types');
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
		Schema::drop('collects');
	}
}
