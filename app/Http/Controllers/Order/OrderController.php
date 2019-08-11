<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;
use App\Models\OrderVariant;

class OrderController extends Controller
{    
	public function index(){
		$order_variant = OrderVariant::paginate(2);

		return view('order.order', [
			'order_variant' => $order_variant,
			'shop_name' => 'test',
			]);
	}

}
