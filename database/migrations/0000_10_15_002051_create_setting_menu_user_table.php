<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setting_menu_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenisUser_id');
            $table->foreign('jenisUser_id')->references('id')->on('jenis_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menu')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_menu_user');
    }
};
