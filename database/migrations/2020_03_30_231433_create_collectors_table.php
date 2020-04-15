<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCollectorsTable.
 */
class CreateCollectorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collectors', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 50);

			$table->string('mondayToFriday', 140);
			$table->string('saturday', 140)->nullable();
			$table->string('sunday', 140)->nullable();
			$table->string('startingAddress', 140)->nullable();
            $table->char('active', 3)->default('off');

			//FK
			$table->unsignedInteger('user_id')->unique();
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
		Schema::dropIfExists('collectors');
	}
}
