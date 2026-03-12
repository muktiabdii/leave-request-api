<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('attachment');

            $table->string('attachment_url')->nullable()->after('reason');
            $table->string('attachment_id')->nullable()->after('attachment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['attachment_url', 'attachment_id']);
            $table->string('attachment')->nullable()->after('reason');
        });
    }
};
