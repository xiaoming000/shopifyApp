<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;
use App\Models\OrderVariant;

class OrderController extends Controller
{
	public function index(Request $request)
	{
		// $order_variant = OrderVariant::paginate(2);

		// $res = [
		// 	'code' => 0,
		// 	'msg' => "",
		// 	'count' => 1000,
		// 	'data' => [
		// 		[
		// 			'id' => 1,
		// 			'username' => 'cc',
		// 			'sex' => 'man',
		// 		],
		// 	]
		// ];

		// // return view('order.order');
		// // return \json_encode($res);
		// // return view('order.order', ['order_variant' => $order_variant]);

		$data['code'] = 0;
		$data['msg'] = '';
		$data['count'] = 4;

		// $list = OrderVariant::all();
		// $list = $list->toArray();
		$data['data'] = [
			[
				'id' => 1,
				'username' => 'cc',
				'sex' => 'man',
			]
		];

		return $data;
	}
}
