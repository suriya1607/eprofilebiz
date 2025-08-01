<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('enable_two_factor_authentication')->default(0)->after('language');
            $table->text('google2fa_secret')->nullable()->after('enable_two_factor_authentication');
        });
    }
};
