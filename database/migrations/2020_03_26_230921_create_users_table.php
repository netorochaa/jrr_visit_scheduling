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
			$table->string('login', 50)->unique();
			$table->string('password', 16);

			//Information
			$table->string('name', 50);
			$table->string('email', 50)->nullable();
			$table->int('type', 1)->default('1');
			$table->int('active', 1)->default('1');
			$table->unsignedInteger('collectors_id')->nullable();

			//FK
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
