<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTemporary extends Model
{
    protected $table = 'order_temporary';

    protected $fillable = ['id', 'shopify_id', 'shop_id', 'shop_name', 'customer_name', 'shop_url', 'email', 'phone', 'total_price', 'financial_status','is_send_email' ,'is_send', 'is_close', 'is_cancel', 'tracking_num', 'goods', 'shopify_created_at', 'shopify_updated_at'];

}
