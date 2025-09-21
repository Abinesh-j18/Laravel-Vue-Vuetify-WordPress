<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('wp_posts', 'priority')) {
            Schema::table('wp_posts', function (Blueprint $table) {
                $table->integer('priority')->default(0);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('wp_posts', 'priority')) {
            Schema::table('wp_posts', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
