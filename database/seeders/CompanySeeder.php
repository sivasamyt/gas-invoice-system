<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Company::insert([
            [
                'name' => 'Simpliaxis Pvt Ltd',
                'address' => '12, MG Road, Bengaluru, India',
                'email_address' => 'info@simpliaxis.com',
                'bn_number' => 'BN-1001',
            ],
            [
                'name' => 'TechNova Solutions',
                'address' => '45 Silicon Avenue, San Francisco, USA',
                'email_address' => 'contact@technova.com',
                'bn_number' => 'BN-1002',
            ],
            [
                'name' => 'Global Insights Ltd',
                'address' => '23 Queen Street, London, UK',
                'email_address' => 'hello@globalinsights.co.uk',
                'bn_number' => 'BN-1003',
            ],
        ]);
    }
}
