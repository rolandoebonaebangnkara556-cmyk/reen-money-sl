<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_id');
            $table->string('type');
            $table->decimal('amount', 20, 2);
            $table->decimal('fee', 20, 2)->default(0);
            $table->decimal('net_amount', 20, 2);
            $table->string('currency')->default('XAF');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->unsignedBigInteger('recipient_account_id')->nullable();
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_id')->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('fraud_score')->default(0);
            $table->string('fraud_status')->default('clean');
            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->unsignedBigInteger('rejected_by_user_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('recipient_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('recipient_account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->foreign('approved_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['user_id', 'type', 'status']);
            $table->index(['account_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
