<?php

namespace Database\Seeders;

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

        Customer::create([
            'name' => 'Jan Novák',
            'street' => 'Korunní 2569/108',
            'city' => 'Praha',
            'zip' => '10100',
            'country' => 'PL',
            'vat_id' => 'CZ12345678',
            'email' => 'janNovak@testemail.cz',
            'phone' => '+420 123 456 789',
        ]);
        Invoice::create([
            'customer_id' => 1,
            'invoice_text' => 'Testovací faktura',
            'issue_date' => '2023-12-01',
            'taxable_supply_date' => '2023-12-01',
            'due_date' => '2023-12-14',
            'currency' => 'CZK',
            'invoice_number' => '20230101',
        ]);

        InvoiceRow::create([
            'invoice_id' => 1,
            'text' => 'Testovací položka',
            'quantity' => 1,
            'unit_price' => 100,
            'vat_rate' => 21
        ]);

        InvoiceRow::create([
            'invoice_id' => 1,
            'text' => 'Testovací položka 2',
            'quantity' => 2,
            'unit_price' => 200,
            'vat_rate' => 21
        ]);
    }
}
