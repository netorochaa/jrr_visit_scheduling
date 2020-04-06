<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePeopleTable.
 */
class CreatePeopleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('people', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 45);
			$table->string('fone', 45);
			$table->string('email', 45)->nullable();
			$table->string('other-fone', 45)->nullable();
			$table->char('type-responsible', 3)->nullable();
			$table->string('name-responsible', 45)->nullable();
			$table->string('fone-responsible', 45);
			$table->string('CPF', 45);
			$table->string('RG', 45)->nullable();
			$table->string('birth', 45);
			$table->string('covenant', 45);
			$table->string('exams', 254);
			$table->string('medication', 254)->nullable();

			//FK
			$table->unsignedInteger('collect_id');
			$table->foreign('collect_id')->references('id')->on('collects');

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
		Schema::drop('people');
	}
}
