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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('invoice_date');
            $table->string('trx_no')->unique();
            $table->string('serial_no')->unique();
            $table->string('clerk');
            $table->string('term_no')->default('0002');
            $table->decimal('amount_due', 10, 2);
            $table->decimal('cash', 10, 2);
            $table->decimal('change', 10, 2);
            $table->decimal('vat_sales', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('vat_exempt', 10, 2);
            $table->decimal('vat_zero', 10, 2);
            $table->decimal('total_sales', 10, 2);
            $table->foreignId('customer_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
