<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->default(0)->comment('用户id')->index();
            $table->string('name', 40)->default('')->comment('用户账号');
            $table->string('product', 255)->default('')->comment('产品');
            $table->string('warning_name', 255)->default('')->comment('故障名称');
            $table->dateTime('occur_time')->comment('发生时间');
            $table->string('type', 10)->default('')->comment('方式：email/phone/ding/webhook/weixin');
            $table->text('message')->comment('发送内容');
            $table->string('account')->default('')->comment('发送至账号');
            $table->tinyInteger('status')->default(1)->comment('是否发送成功,1成功，0失败');
            $table->tinyInteger('times')->default(1)->comment('已发送次数');
            $table->tinyInteger('show')->default(1)->comment('是否展示，1显示，0不显示');
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
        Schema::dropIfExists('warning_logs');
    }
}
