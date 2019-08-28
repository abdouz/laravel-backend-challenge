<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemaAndSample extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(file_get_contents(database_path('dump.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute');
        Schema::dropIfExists('attribute_value');
        Schema::dropIfExists('category');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('department');
        Schema::dropIfExists('order_detail');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product');
        Schema::dropIfExists('product_attribute');
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('review');
        Schema::dropIfExists('shipping');
        Schema::dropIfExists('shipping_region');
        Schema::dropIfExists('shopping_cart');
        Schema::dropIfExists('tax');
    }
}
