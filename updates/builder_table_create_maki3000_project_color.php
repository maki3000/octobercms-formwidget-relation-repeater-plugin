<?php namespace Maki3000\Project\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMaki3000ProjectColor extends Migration
{
    public function up()
    {
        Schema::create('maki3000_project_color', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('color_value');
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('maki3000_project_color');
    }
}
