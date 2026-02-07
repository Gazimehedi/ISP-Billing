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
        Schema::create('sms_templates', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('type')->unique(); // bill_confirmation, new_client, etc.
            $blueprint->string('title')->nullable();
            $blueprint->text('content')->nullable();
            $blueprint->boolean('status')->default(false);
            $blueprint->string('message_type')->default('non-masking'); // non-masking, masking, fixed-number
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
