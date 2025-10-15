<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
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
            $amounts = [
                  'Rp90,405','Rp300,000','Rp98,910','Rp482,750','Rp103,700','Rp94,250','Rp341,000','Rp101,325','Rp89,000','Rp509,000','Rp107,900','Rp267,500','Rp91,875','Rp104,750','Rp524,750','Rp101,850','Rp582,500','Rp110,000','Rp372,500','Rp95,300','Rp102,650','Rp614,000','Rp97,400','Rp446,000','Rp114,200','Rp99,500','Rp629,750','Rp94,250','Rp105,800','Rp404,000','Rp111,050','Rp551,000','Rp96,350','Rp330,500','Rp107,900','Rp100,550','Rp477,500','Rp112,100','Rp104,750','Rp687,500','Rp115,250','Rp99,500'
            ];
            $paymentMethods = [
                'QRIS', 'BCA Virtual Account', 'DANA', 'GoPay', 'BNI Virtual Account', 'ShopeePay', 'Mandiri Virtual Account'
            ];

            $customDates = [
                '01/09/2025 10:22','01/09/2025 18:41','02/09/2025 9:15','02/09/2025 16:30','03/09/2025 11:05','03/09/2025 20:01','04/09/2025 8:55','04/09/2025 14:12','05/09/2025 10:48','05/09/2025 17:20','06/09/2025 12:33','06/09/2025 19:55','07/09/2025 9:27','07/09/2025 15:40','07/09/2025 21:00','08/09/2025 8:30','08/09/2025 14:52','08/09/2025 21:18','09/09/2025 9:24','09/09/2025 16:05','09/09/2025 22:31','10/09/2025 10:15','10/09/2025 17:40','10/09/2025 23:59','11/09/2025 11:30','11/09/2025 18:01','11/09/2025 23:20','12/09/2025 9:55','12/09/2025 15:10','12/09/2025 21:48','13/09/2025 10:00','13/09/2025 14:22','13/09/2025 20:30','14/09/2025 8:45','14/09/2025 12:15','14/09/2025 16:00','14/09/2025 19:30','14/09/2025 22:00','15/09/2025 9:00','15/09/2025 15:30','15/09/2025 22:10','16/09/2025 8:40'
            ];

            foreach ($invoiceIds as $i => $invoiceId) {
                $amountStr = $amounts[$i];
                $amountInt = (int)str_replace(["Rp", ","], "", $amountStr);
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                $transactionDate = \DateTime::createFromFormat('d/m/Y H:i', $customDates[$i])->format('Y-m-d H:i:s');

                \App\Models\Transaction::create([
                    'id' => \Str::uuid()->toString(),
                    'invoice_id' => $invoiceId,
                    'amount_paid' => $amountInt,
                    'payment_method' => $paymentMethod,
                    'transaction_date' => $transactionDate,
                ]);
            }

            $this->command->info('✓ Created ' . count($invoiceIds) . ' transactions successfully!');
    }
}
