<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     protected $fillable = ['name','sku','price_per_liter','description'];

     public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_products')
                    ->withTimestamps();
    }
}
