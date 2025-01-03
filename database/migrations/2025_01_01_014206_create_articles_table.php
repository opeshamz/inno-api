<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('articles', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->text('title');
                $table->longText('content');
                $table->date('publish_date');
                $table->string('category');
                $table->string('source');
                $table->string('news_url')->nullable();
                $table->string('img_url')->nullable();
                $table->string('author')->nullable();
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
