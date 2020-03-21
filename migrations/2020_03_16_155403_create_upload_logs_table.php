<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUploadLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upload_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module')->comment('模块');
            $table->string('path')->comment('文件地址');
            $table->string('extname')->default('')->comment('扩展名');
            $table->tinyInteger('existed')->comment('是否存在');
            $table->tinyInteger('public')->comment('是否公开访问0,1');
            $table->timestamps();
            $table->unique('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_logs');
    }
}
