<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate table untuk fresh seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Custom list of users (name & phone)
        $customUsers = [
            ['name' => 'I Nyoman Sutapa Berata', 'phone' => null],
            ['name' => 'I Ketut Karsa', 'phone' => null],
            ['name' => 'I Made Rantiasa', 'phone' => null],
            ['name' => 'Drs. Wayan Karta', 'phone' => null],
            ['name' => 'I Gede Harta Wijaya', 'phone' => null],
            ['name' => 'I Wayan Selamat', 'phone' => '081558670345'],
            ['name' => 'I Made Rarud', 'phone' => null],
            ['name' => 'I Wayan Karma', 'phone' => null],
            ['name' => 'I Made Muguk', 'phone' => null],
            ['name' => 'I Wayan Dasi', 'phone' => null],
            ['name' => 'I Ketut Jimat', 'phone' => null],
            ['name' => 'I Wayan Nyeneng', 'phone' => null],
            ['name' => 'I Ketut Sama', 'phone' => null],
            ['name' => 'I Wayan Supadmia', 'phone' => null],
            ['name' => 'I Wayan Ari Sukadana', 'phone' => null],
            ['name' => 'Ni Wayan Suartini', 'phone' => null],
            ['name' => 'I Wayan Karba', 'phone' => null],
            ['name' => 'I Ketut Normin', 'phone' => null],
            ['name' => 'I Nyoman Norman', 'phone' => null],
            ['name' => 'I Ketut Karsa', 'phone' => null],
            ['name' => 'I Made Sarwa', 'phone' => null],
            ['name' => 'I Made rantiasa', 'phone' => null],
            ['name' => 'I Made Sumarna', 'phone' => null],
            ['name' => 'I Made Tropi Adi', 'phone' => null],
            ['name' => 'I Ketut Sadu Wiguna', 'phone' => null],
            ['name' => 'I Wayan Suganda', 'phone' => null],
            ['name' => 'I Made Marda', 'phone' => null],
            ['name' => 'I Putu Indra Rakeswara', 'phone' => null],
            ['name' => 'I Made Sudimawan', 'phone' => null],
            ['name' => 'I Made Karbin', 'phone' => null],
            ['name' => 'I Made karya', 'phone' => null],
            ['name' => 'I Made Sarwa, S.Pd.', 'phone' => null],
            ['name' => 'I Wayan Selamat, SE', 'phone' => '081558670345'],
            ['name' => 'I Wayan Terem, S.Ag', 'phone' => '081916560883'],
            ['name' => 'I Nyoman Anto Wijaya,S.Pd .SD', 'phone' => '082145555388'],
            ['name' => 'I Wayan Jarwi', 'phone' => '081959030998'],
            ['name' => 'I Ketut Rindu, SE', 'phone' => '082146067388'],
            ['name' => 'I Komang Suwedi, SP', 'phone' => '081238156556'],
            ['name' => 'I Made Beneng', 'phone' => '081239899164'],
            ['name' => 'I Made Sarwa', 'phone' => '081338753392'],
            ['name' => 'I Nengah Sandi', 'phone' => '081246165043'],
            ['name' => 'I Made Merta No', 'phone' => '087881602831'],
            ['name' => 'I Ketut Punduh No', 'phone' => '081938493327'],
            ['name' => 'I Wayan Diar, SST.Par No', 'phone' => '08123809080'],
            ['name' => 'I Ketut Pinpin No', 'phone' => '081353273180'],
            ['name' => 'I Nengah Rija No', 'phone' => '081353456976'],
            ['name' => 'I Made Misi No', 'phone' => '081338340613'],
            ['name' => 'I Made Merta No', 'phone' => '081916567636'],
            ['name' => 'I Ketut Tuptup No', 'phone' => '081237517344'],
            ['name' => 'I Made Buda No', 'phone' => '081239657234'],
            ['name' => 'I Made Yasa Saputra No', 'phone' => '081238502699'],
            ['name' => 'I Made Teka No', 'phone' => '08311855371'],
            ['name' => 'I Wayan Ratep', 'phone' => '081805695700'],
            ['name' => 'I Putu Wiguna', 'phone' => '082144177687'],
            ['name' => 'I Ketut Kardi', 'phone' => '085739864869'],
            ['name' => 'I Made Narka', 'phone' => null],
            ['name' => 'I Made Patri', 'phone' => '081246555615'],
            ['name' => 'I Made Tindih', 'phone' => '082146072335'],
            ['name' => 'I Wayan Berata', 'phone' => '082144177704'],
            ['name' => 'I Made Monjong', 'phone' => null],
            ['name' => 'I Wayan Ngawit', 'phone' => '087735907909'],
            ['name' => 'I Ketut Sukada', 'phone' => '081337309524'],
            ['name' => 'I Nyoman Suarta', 'phone' => '082147367372'],
            ['name' => 'I Wayan Ediana', 'phone' => '081310207248'],
        ];

        $emailMap = [];
        $roles = ['farmer', 'worker', 'driver'];
        
        $makeUniqueEmail = function($name, &$emailMap, $domain) {
            // Buat base email dari nama, hapus karakter khusus
            $baseEmail = strtolower(preg_replace('/[^a-z0-9]/i', '', str_replace([' ', '.', ','], '', $name)));
            
            // Jika email sudah ada, tambahkan counter
            $counter = 1;
            $email = $baseEmail . $domain;
            
            while (isset($emailMap[$email])) {
                $email = $baseEmail . $counter . $domain;
                $counter++;
            }
            
            $emailMap[$email] = true;
            return $email;
        };

        // Insert custom users
        foreach ($customUsers as $user) {
            $name = $user['name'];
            $phone = $user['phone'] ?? null;
            
            if (!$phone || $phone === '-' || strlen($phone) < 10) {
                $phone = '08' . rand(100000000, 999999999);
            }
            
            $email = $makeUniqueEmail($name, $emailMap, '@gmail.com');
            
            User::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'role' => $roles[array_rand($roles)],
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'password' => Hash::make('password'),
            ]);
        }

        // Updated modern Indonesian names
        $modernNames = [
            'Dwi', 'Muhammad', 'Nur', 'Dewi', 'Tri', 'Dian', 'Sri', 'Putri', 
            'Eka', 'Sari', 'Ayu', 'Wahyu', 'Indah', 'Siti', 'Ika', 'Agus', 
            'Fitri', 'Ratna', 'Andi', 'Agung', 'Ahmad', 'Kurniawan', 'Ilham', 
            'Budi', 'Adi', 'Eko', 'Nurul', 'Putra', 'Ni', 'Arif', 'Puspita', 
            'Ari', 'Indra', 'Dyah', 'Rizki', 'Maria', 'Ratih', 'Pratiwi', 
            'Kartika', 'Wulandari', 'Fajar', 'Bayu', 'Lestari', 'Anita', 
            'Muhamad', 'Kusuma', 'Rahmawati', 'Fitria', 'Retno', 'Kurnia', 
            'Novita', 'Aditya', 'Ria', 'Nugroho'
        ];

        $faker = \Faker\Factory::create('id_ID');
        
        // Generate 40 random users with modern names
        for ($i = 0; $i < 40; $i++) {
            $firstName = $faker->randomElement($modernNames);
            $lastName = $faker->lastName;
            $name = $firstName . ' ' . $lastName;
            
            $phone = '08' . rand(100000000, 999999999);
            $email = $makeUniqueEmail($name, $emailMap, '@gmail.com');
            
            User::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone,
                'role' => $roles[array_rand($roles)],
                'is_active' => true,
                'email_verified' => true,
                'phone_verified' => true,
                'password' => Hash::make('password'),
            ]);
        }

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@goagrolink.com',
            'phone_number' => '082147389276',
            'role' => 'admin',
            'is_active' => true,
            'email_verified' => true,
            'phone_verified' => true,
            'password' => Hash::make('agrolink123'),
        ]);

        $this->command->info('✓ Created ' . User::count() . ' users successfully!');
    }
}