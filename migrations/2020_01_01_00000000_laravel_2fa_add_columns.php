<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Laravel2faAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->getTable(), function (Blueprint $table) {
            $table->boolean('laravel2fa_enabled')->default(false);
            $table->text('laravel2fa_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->getTable(), function (Blueprint $table) {
            $table->dropColumn('laravel2fa_enabled');
        });

        Schema::table($this->getTable(), function (Blueprint $table) {
            $table->dropColumn('laravel2fa_data');
        });
    }

    /**
     * Get the users table name
     *
     * @return string
     */
    private function getTable(): string
    {
        $usersClass = config('laravel-2fa.user_class');

        return (new $usersClass())->getTable();
    }
}
