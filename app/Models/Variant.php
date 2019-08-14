<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Variant extends Model
{
    protected $table = 'variant_test1';

    protected $fillable = ['id', 'sku', 'ali_item_id', 'shopify_variant_id', 'origin_price', 'seller_price', 'stock', 'title',
        'image_url', 'ali_item_url', 'shopify_image_id', 'is_update', 'inventory_item_id', 'shopify_product_id', 'shop_id', 'des_url'];

}
