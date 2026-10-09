<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('level')->default(1);
            $table->string('status')->default('pending');
            $table->boolean('phone_verified')->default(false);
            $table->boolean('email_verified')->default(false);
            $table->boolean('document_verified')->default(false);
            $table->boolean('address_verified')->default(false);
            $table->boolean('biometric_verified')->default(false);
            $table->boolean('video_verified')->default(false);
            $table->boolean('source_of_funds_verified')->default(false);
            $table->json('documents')->nullable();
            $table->unsignedBigInteger('verified_by_user_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('verified_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_profiles');
    }
};
