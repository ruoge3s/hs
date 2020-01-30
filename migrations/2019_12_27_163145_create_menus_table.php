<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pid')->default(0)->comment('父级ID');
            $table->string('name')->comment('名称(根据前端定义的路由名称)');
            $table->string('describe')->comment('描述');
            $table->timestamps();
            $table->unique('name');
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `menus` comment '前端菜单表'");

        Schema::create('role_has_menus', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->unsignedBigInteger('menu_id')->comment('菜单ID');
            $table->index('role_id');
            $table->index('menu_id');
        });
        \Hyperf\DbConnection\Db::statement("ALTER TABLE `role_has_menus` comment '角色关联菜单表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('role_has_menus');
    }
}
