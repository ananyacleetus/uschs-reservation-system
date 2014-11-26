<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("reservations", function($table)
		{
			$table->increments("id");
			$table->string("teacher_name");
			$table->string("teacher_email");
			$table->integer("room_number");
			$table->string("mods");
			$table->string("date");
			$table->integer("cart_id")->unsigned();
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
		Schema::drop("reservations");
	}

}
