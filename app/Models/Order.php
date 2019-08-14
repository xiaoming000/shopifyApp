<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OrderVariant;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['id', 'shopify_id', 'shop_token_id', 'name', 'email', 'phone', 'total_price', 'financial_status','is_send_email' ,'is_send', 'is_close', 'shopify_created_at', 'shopify_updated_at'];

    public function hasManyOrderVariant()
    {
        return $this->hasMany(OrderVariant::class);
    }
}
