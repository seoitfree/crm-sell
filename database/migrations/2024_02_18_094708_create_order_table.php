<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('created_by', 36)->index();
            $table->string('modified_user_id', 36)->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


            $table->integer('amount_in_order'); //К-ть в замовленні
            $table->integer('amount_in_order_paid');
            $table->float('sell_price', 10,2);
            $table->float('cost', 10,2)->default(0.0);


            $table->dateTime('date_check')->nullable();
            $table->dateTime('order_date');
            $table->string('order_number', 50);
            $table->string('vendor_code', 100);//Артикул
            $table->string('goods_name', 150);
            $table->text('manager_comment');
            $table->text('comment');


            $table->string('comfy_code', 50);
            $table->string('comfy_goods_name', 200);
            $table->string('comfy_brand', 50);
            $table->string('comfy_category', 100);
            $table->float('comfy_price', 10,2);

            $table->string('status', 50)->index();
            $table->string('defect', 50)->index();


            $table->string('manager', 36)->index();
            $table->string('provider_start', 36)->index();
        });

        if(!Schema::hasTable('shipments')) {
            Schema::create('shipments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid("order_id")->index();
                $table->date('shipment_date');
                $table->integer('amount');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
                $table->string('created_by', 36)->index();

                $table->foreign("order_id")
                    ->references("id")
                    ->on("orders")
                    ->onDelete('cascade');
            });
        }

        /*if(!Schema::hasTable('orders_audit')) {
            Schema::create('orders_audit', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid("parent_id")->index();


                $table->foreign("parent_id")
                    ->references("id")
                    ->on("orders")
                    ->onDelete('cascade');
            });
        }*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('orders_audit');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('orders');
    }
};