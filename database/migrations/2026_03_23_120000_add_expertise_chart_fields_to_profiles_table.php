<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('chart_label_1')->nullable()->after('avatar_url');
            $table->unsignedTinyInteger('chart_value_1')->nullable()->after('chart_label_1');
            $table->string('chart_label_2')->nullable()->after('chart_value_1');
            $table->unsignedTinyInteger('chart_value_2')->nullable()->after('chart_label_2');
            $table->string('chart_label_3')->nullable()->after('chart_value_2');
            $table->unsignedTinyInteger('chart_value_3')->nullable()->after('chart_label_3');
            $table->string('chart_label_4')->nullable()->after('chart_value_3');
            $table->unsignedTinyInteger('chart_value_4')->nullable()->after('chart_label_4');
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'chart_label_1',
                'chart_value_1',
                'chart_label_2',
                'chart_value_2',
                'chart_label_3',
                'chart_value_3',
                'chart_label_4',
                'chart_value_4',
            ]);
        });
    }
};