<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CompanyProductSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $products = Product::all();

        $data = [];

        foreach ($companies as $company) {
            foreach ($products as $product) {
                $data[] = [
                    'company_id' => $company->id,
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('company_products')->insert($data);
    }
}
