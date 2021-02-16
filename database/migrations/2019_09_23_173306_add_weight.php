<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->integer("packet")->default(12);
        });
        Schema::table('queries', function (Blueprint $table) {
            $table->integer("price_per_packet");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn("packet");
            });
        }
        catch (Exception $e) {}
        try {
            Schema::table('queries', function (Blueprint $table) {
                $table->dropColumn("price_per_packet");
            });
        }
        catch (Exception $e) {}
    }
}
