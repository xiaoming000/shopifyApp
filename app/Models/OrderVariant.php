<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 订单商品信息表
 *
 * @author dengweixiong
 */
class OrderVariant extends Model
{
    protected $table = 'order_variant';

    protected $fillable = ['id', 'order_id', 'variant_id', 'quantity'];
}
