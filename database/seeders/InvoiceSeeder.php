<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            $invoiceIds = [
                'INV/20250901/001','INV/20250901/002','INV/20250902/003','INV/20250902/004','INV/20250903/005','INV/20250903/006','INV/20250904/007','INV/20250904/008','INV/20250905/009','INV/20250905/010','INV/20250906/011','INV/20250906/012','INV/20250907/013','INV/20250907/014','INV/20250907/015','INV/20250908/016','INV/20250908/017','INV/20250908/018','INV/20250909/019','INV/20250909/020','INV/20250909/021','INV/20250910/022','INV/20250910/023','INV/20250910/024','INV/20250911/025','INV/20250911/026','INV/20250911/027','INV/20250912/028','INV/20250912/029','INV/20250912/030','INV/20250913/031','INV/20250913/032','INV/20250913/033','INV/20250914/034','INV/20250914/035','INV/20250914/036','INV/20250914/037','INV/20250914/038','INV/20250915/039','INV/20250915/040','INV/20250915/041','INV/20250916/042'
            ];

            foreach ($invoiceIds as $invoiceId) {
                \App\Models\Invoice::create([
                    'id' => $invoiceId,
                    'farmer_id' => '0199e5ed-1ae2-7125-84f5-11830b8d7f71',
                    'status' => 'paid',
                    'amount' => 100000,
                    'due_date' => now(),
                ]);
            }

            $this->command->info('✓ Created ' . count($invoiceIds) . ' invoices successfully!');
    }
}
