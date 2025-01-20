<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutual', function (Blueprint $table) {
            $table->integer("id")->autoIncrement();
            $table->string("username");
            $table->integer("posts")->nullable()->default(null);
            $table->integer("followers")->nullable()->default(null);
            $table->integer("following")->nullable()->default(null);
            $table->text("bio")->nullable()->default(null);
            $table->boolean("is_verified")->default(false);
            $table->boolean("is_private")->default(true);
            $table->boolean("is_scraped")->default(false);
            $table->boolean("is_followers_scraped")->default(false);

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
        Schema::dropIfExists('mutual');
    }
}
