<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->default('')->comment('用户名');
            $table->string('nickname')->default('')->comment('昵称');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('password')->default('')->comment('密码');
            $table->tinyInteger('enable')->default(0)->comment('启用');
            $table->timestamps();
            $table->unique('username');
            $table->unique('email');
        });
        $builder = \Hyperf\DbConnection\Db::table('users');
        $builder->insert([
            'username'  => 'administrator',
            'nickname'  => '超级管理员',
            'email'     => env('ADMIN_EMAIL', 'admin@hs.com'),
            'password'  => password_hash(env('ADMIN_PASS', 'demo2020'), PASSWORD_BCRYPT),
            'enable'    => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
