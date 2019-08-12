<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OrderVariant;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['id', 'shopify_id', 'shop_id', 'email', 'phone', 'is_paid', 'is_send', 'is_close'];

    public function hasManyVariant()
    {
        return $this->hasMany(OrderVariant::class);
    }
}
