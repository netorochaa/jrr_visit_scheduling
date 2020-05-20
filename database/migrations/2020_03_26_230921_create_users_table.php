<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
/**
 * Class CreateUsersTable.
 */
class CreateUsersTable extends Migration
{
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');

			//Auth
			$table->string('email', 50)->unique();
            $table->string('password');
            $table->rememberToken();

			//Information
			$table->string('name', 50);
            $table->char('type', 2)->default(1);
			$table->char('active', 3)->default('on');

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
