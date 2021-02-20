<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekognitionResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekognition_resources', function (Blueprint $table) {
            $table->id();
            $table->string('resource_original_name', 1024)->comment('ファイルのオリジナル名');
            $table->string('resource_path', 1024)->comment('ファイルの保存先パス');
            $table->string('rekognition_type', 20)->comment('画像解析タイプ');
            $table->unsignedBigInteger('user_id')->nullable()->comment('所有所');
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
        Schema::dropIfExists('rekognition_resources');
    }
}
