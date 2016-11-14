<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastReadChapterColumnToMangasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->boolean('last_read_chapter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mangas', function (Blueprint $table) {
            $table->dropColumn('last_read_chapter');
        });
    }
}
