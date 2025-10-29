<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['company_id','first_name','last_name','email_address','mobile_number','send_docket_to'];

    public function company(){ return $this->belongsTo(Company::class); }
    public function addresses(){ return $this->hasMany(Address::class); }
    public function billingAddress(){ return $this->hasOne(Address::class)->where('type','billing'); }
    public function deliveryAddress(){ return $this->hasOne(Address::class)->where('type','delivery'); }
    public function orders(){ return $this->hasMany(Order::class); }
}
