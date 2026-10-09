<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('id_document_type')->nullable();
            $table->string('id_document_number')->nullable();
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('occupation')->nullable();
            $table->string('source_of_income')->nullable();
            $table->string('pin_hash')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_method')->nullable();
            $table->string('status')->default('active');
            $table->string('aml_status')->default('clear');
            $table->boolean('politically_exposed')->default(false);
            $table->unsignedBigInteger('created_at_agency_id')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->foreign('created_at_agency_id')->references('id')->on('agencies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
