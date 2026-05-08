<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('id')->constrained('customers')->nullOnDelete();
            $table->boolean('is_admin')->default(false)->after('customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
            $table->dropColumn('is_admin');
        });
    }
};