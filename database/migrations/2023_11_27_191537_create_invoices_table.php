<?php

use App\Enums\Currency;
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
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('issue_date');
            $table->date('taxable_supply_date');
            $table->date('due_date');
            $table->enum('currency', Currency::getCases());
            $table->enum('status', \App\Enums\InvoiceStatus::getCases());
            $table->string('invoice_number');
            $table->string('oss_regime');
            $table->string('oss_info');
            $table->enum('oss_country', \App\Enums\Country::getCases());
            $table->string('oss_vat_id');
            $table->string('oss_taxable_supply');
            $table->string('oss_taxable_supply_currency');
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
