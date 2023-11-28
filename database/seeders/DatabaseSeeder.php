<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\VatRate;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceRow;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //  $table->id();
        //            $table->string('name')->nullable();
        //            $table->string('street')->nullable();
        //            $table->string('city')->nullable();
        //            $table->string('zip')->nullable();
        //            $table->enum('country', [\App\Enums\Country::getCases()])->default(\App\Enums\Country::Cesko)->nullable();
        //            $table->string('vat_id');
        //            $table->string('email')->nullable();
        //            $table->string('phone')->nullable();
        //            $table->string('bank_account')->nullable();
        //            $table->string('bank_code')->nullable();
        //            $table->string('bank_name')->nullable();
        //            $table->timestamps();

        Customer::create([
            'name' => 'Jan Novák',
            'street' => 'Korunní 2569/108',
            'city' => 'Praha',
            'zip' => '10100',
            'country' => 'CZ',
            'vat_id' => 'CZ12345678',
            'email' => 'janNovak@testemail.cz',
            'phone' => '+420 123 456 789',
            'bank_account' => '123456789',
            'bank_code' => '1234',
            'bank_name' => 'Česká spořitelna',
        ]);
        Invoice::create([
            'customer_id' => 1,
            'issue_date' => '2021-01-01',
            'taxable_supply_date' => '2021-01-01',
            'due_date' => '2021-01-01',
            'currency' => 'CZK',
            'status' => 'Nezaplaceno',
            'invoice_number' => '2021-01-01',
            'oss_regime' => 'OSS',
            'oss_info' => 'OSS',
            'oss_country' => 'CZ',
            'oss_vat_id' => 'CZ12345678',
            'oss_taxable_supply' => 'OSS',
            'oss_taxable_supply_currency' => 'CZK',
        ]);

        InvoiceRow::create([
            'invoice_id' => 1,
            'text' => 'Testovací položka',
            'quantity' => 1,
            'unit_price' => 100,
            'vat_rate' => 21
        ]);
    }

}
