<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'address_id', 'coupon_id', 'payment_method', 'payment_status', 
        'status', 'subtotal', 'shipping', 'tax', 'discount', 'total'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
