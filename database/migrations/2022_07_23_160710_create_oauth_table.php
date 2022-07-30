<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('oauth.table', 'oauth'), function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('名称');
            $table->string('device')->comment('设备标识');
            $table->morphs('tokenable');
            $table->string('access_token')->unique()->comment('凭证');
            $table->string('refresh_token')->unique()->comment('刷新凭证');
            $table->timestamp('access_token_expire_at')->nullable()->comment('凭证超时时间');
            $table->timestamp('refresh_token_expire_at')->nullable()->comment('刷新凭证超时时间');
            $table->timestamp('last_used_at')->nullable()->comment('最近访问时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('最新更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('oauth.table', 'oauth'));
    }
};
