<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveredLiter extends Model
{
    use HasFactory;
    protected $fillable = ['order_item_id','delivered_liters','delivered_at'];
}
