<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderVariant extends Model
{
    protected $table = 'order_variant';

    protected $fillable = ['id', 'order_id', 'variant_id', 'quantity'];
}
