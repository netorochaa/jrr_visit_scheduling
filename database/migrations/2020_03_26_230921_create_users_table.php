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
			$table->string('password', 254);

			//Information
			$table->string('name', 50);
            $table->char('type', 2)->default(1);
			$table->char('active', 3)->default('off');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
