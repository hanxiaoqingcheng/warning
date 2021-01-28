<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningTplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_tpls', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->comment('用户或用户组id')->index();
            $table->string('uname', 40)->default('')->comment('用户名')->index();
            $table->string('product', 255)->default('')->comment('产品');
            $table->string('warning_name', 255)->default('')->comment('故障名称');
            $table->text('warning_tpl')->comment('预警信息模板');
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
        Schema::dropIfExists('warning_tpls');
    }
}
