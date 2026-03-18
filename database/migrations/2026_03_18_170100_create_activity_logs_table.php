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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('country', 120)->nullable();
            $table->string('method', 10);
            $table->text('url');
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();

            $table->index('ip_address');
            $table->index('visited_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
