<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('account_number')->unique();
            $table->string('iban')->nullable();
            $table->decimal('balance', 20, 2)->default(0);
            $table->decimal('available_balance', 20, 2)->default(0);
            $table->decimal('blocked_balance', 20, 2)->default(0);
            $table->string('currency')->default('XAF');
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by_agency_id')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('created_by_agency_id')->references('id')->on('agencies')->nullOnDelete();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
