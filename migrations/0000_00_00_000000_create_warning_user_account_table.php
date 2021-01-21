<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningUserAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_user_account', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->default(0)->comment('用户id')->index();
            $table->string('uname', 40)->default('')->comment('用户名');
            $table->string('type', 10)->default('')->comment('方式：email/phone/ding等等');
            $table->string('account', 255)->default('')->comment('产品');
            $table->tinyInteger('show')->default(1)->comment('是否展示，1显示，-1不显示');
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
        Schema::dropIfExists('warning_user_account');
    }
}
