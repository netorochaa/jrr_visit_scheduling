<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable.
 */
class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');

			//Auth
			$table->string('email', 50)->unique();
			$table->string('password', 16);

			//Information
			$table->string('name', 50);
			$table->char('type', 1)->default('1');
			$table->char('active', 3)->default('on');

			//FK
			$table->unsignedInteger('collectors_id')->nullable();
			$table->foreign('collectors_id')->references('id')->on('collectors');

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
		Schema::drop('users');
	}
}
