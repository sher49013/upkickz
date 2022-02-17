<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification_users', function (Blueprint $table) {
            $table->id();
            $table->string('device_token',510)->nullable();
            $table->string('device_id',510)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('date_updated')->nullable();

            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null')->nullable();

            $table->unsignedBigInteger('push_platform_id')->index()->nullable();
            $table->foreign('push_platform_id')->references('id')->on('push_notification_platforms')
                ->onUpdate('cascade')
                ->onDelete('set null')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_notification_users');
    }
}
