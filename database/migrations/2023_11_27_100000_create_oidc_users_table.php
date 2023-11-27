<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        // columns are named to closely match HYVOR's model
        Schema::create('oidc_users', function ($table) {
            $table->id();
            $table->timestamps();
            $table->string('sub')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('picture_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('location')->nullable();
            $table->string('bio')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('oidc_users');
    }

};