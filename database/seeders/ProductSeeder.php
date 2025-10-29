<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Oxygen Gas Cylinder',
                'sku' => 'OX-001',
                'price_per_liter' => 2.50,
                'description' => 'High-purity medical oxygen gas cylinder suitable for hospitals and clinics.',
            ],
            [
                'name' => 'Nitrogen Gas Cylinder',
                'sku' => 'N2-002',
                'price_per_liter' => 1.80,
                'description' => 'Industrial-grade nitrogen gas for laboratory and manufacturing use.',
            ],
            [
                'name' => 'Carbon Dioxide Gas Cylinder',
                'sku' => 'CO2-003',
                'price_per_liter' => 1.20,
                'description' => 'Food-grade CO2 cylinder ideal for beverages and welding processes.',
            ],
            [
                'name' => 'Argon Gas Cylinder',
                'sku' => 'AR-004',
                'price_per_liter' => 3.10,
                'description' => 'Pure argon gas used in TIG and MIG welding applications.',
            ],
            [
                'name' => 'Helium Gas Cylinder',
                'sku' => 'HE-005',
                'price_per_liter' => 4.00,
                'description' => 'High-quality helium gas used for balloons, leak detection, and scientific experiments.',
            ],
        ]);
    }
}
