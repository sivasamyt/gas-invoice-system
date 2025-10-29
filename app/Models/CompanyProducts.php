<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProduct extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * By default, Laravel assumes the table name is the plural form of the model name.
     * But since this is a pivot table, we explicitly specify it.
     */
    protected $table = 'company_products';

    /**
     * The attributes that are mass assignable.
     * Allows you to use mass assignment when creating/updating records.
     */
    protected $fillable = [
        'company_id',
        'product_id',
    ];

    /**
     * Relationship: Each record belongs to one company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship: Each record belongs to one product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
