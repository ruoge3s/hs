<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreatePermissionHasGroupTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission_has_group', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('permission_id与permission一一对应');
            $table->integer('gid')->comment('组ID');
        });
        Schema::create('permission_group', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('组ID');
            $table->string('name')->comment('组名称');
            $table->integer('sort')->comment('排序');
            $table->timestamps();
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `permission_has_group` comment '权限关联分组表'");
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `permission_group` comment '权限组表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_has_group');
        Schema::dropIfExists('permission_group');
    }
}
