<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfSortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_sorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekognition_resource_id')->nullable()->constrained();
            $table->string('sorted_pdf_path', 1024)->comment('ソートされたPDFファイルの保存先パス');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdf_sorts');
    }
}
