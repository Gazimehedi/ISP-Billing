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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->text('content');
            $table->string('provider')->nullable(); // bulksmsbd, mimsms
            $table->string('message_type')->default('non-masking');
            $table->string('type')->default('text'); // text, unicode
            $table->integer('sms_count')->default(1);
            $table->string('status')->default('pending'); // success, failed
            $table->text('response_msg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
