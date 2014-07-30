<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Initial extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function()
        {
            Schema::create('tasks', function($table)
            {
                $table->engine = 'InnoDB';

                $table->integer('task_id', true)->unsigned();
                $table->integer('blocker_id')->unsigned();
                $table->text('description');
                $table->integer('added')->unsigned();
                $table->integer('due')->unsigned();
                $table->boolean('recurring')->unsigned();
                $table->integer('gap')->unsigned();
                $table->tinyInteger('season')->unsigned();
                $table->decimal('complete', 3, 2)->unsigned();

                $table->index('blocker_id');
                $table->index('due');
            });

            Schema::create('users', function($table)
            {
                $table->engine = 'InnoDB';

                $table->integer('user_id', true)->unsigned();
                $table->string('email', 255);
                $table->string('name', 255);

                $table->index('email');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function()
        {;
            Schema::drop('tasks');
            Schema::drop('users');
        });
    }

}
