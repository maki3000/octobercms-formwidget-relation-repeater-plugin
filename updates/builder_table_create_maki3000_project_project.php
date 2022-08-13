<?php namespace Maki3000\Project\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMaki3000ProjectProject extends Migration
{
    public function up()
    {
        Schema::create('maki3000_project_project', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->text('content');
            $table->text('basics')->nullable();
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maki3000_project_project');
    }
}
