<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name','address','email_address','bn_number'];

    public function customers(){ return $this->hasMany(Customer::class); }
    public function invoices(){ return $this->hasMany(Invoice::class); }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'company_products')
                    ->withTimestamps();
    }
}
