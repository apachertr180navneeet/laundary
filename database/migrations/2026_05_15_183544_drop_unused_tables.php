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
        Schema::dropIfExists('item_detail');
        Schema::dropIfExists('items');
        Schema::dropIfExists('splash_screens');
        Schema::dropIfExists('phone_otps');
        Schema::dropIfExists('email_otps');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('notification_users');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('app_users');
    }

    public function down(): void
    {
        // Re-creating these is unnecessary — they were dead tables from removed features.
    }
};
