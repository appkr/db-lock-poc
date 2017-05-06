<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('상품명');
            $table->unsignedInteger('stock')->default(0)->comment('재고수량');
            $table->unsignedInteger('price')->default(0)->comment('가격');
            $table->text('description')->nullable()->comment('상품 설명');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
