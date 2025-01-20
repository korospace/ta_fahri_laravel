<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_upload', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->enum("progress_status", ['ongoing','completed']);
            $table->enum("tab_active", ['tab_upload_data','tab_mutual','tab_mutual_detail','tab_mutual_followers','tab_node_graph']);
            $table->enum("tab_upload_data", ['enable','disabled','ongoing','finish']);
            $table->enum("tab_mutual", ['enable','disabled','ongoing','finish']);
            $table->enum("tab_mutual_detail", ['enable','disabled','ongoing','finish']);
            $table->enum("tab_mutual_followers", ['enable','disabled','ongoing','finish']);
            $table->enum("tab_node_graph", ['enable','disabled','ongoing','finish']);
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
        Schema::dropIfExists('progress_upload');
    }
}
