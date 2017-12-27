<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $defaultGuard = Config::get('auth.defaults.guard');

            $table->increments('id');
            $table->string('name')->comment('권한명');
            $table->string('guard')->default($defaultGuard)->comment('적용할 가드');
            $table->timestamps();

            $table->unique(['name', 'guard']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
