<?php namespace BootstrapHunter\Support\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateItemsTable extends Migration
{

    public function up()
    {
        Schema::create('bootstraphunter_support_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('backend')->unsigned()->default('0');
            $table->text('message');
            $table->tinyInteger('message_type')->unsigned()->default('1');
            $table->timestamps();

            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::drop('bootstraphunter_support_messages');
    }
}
