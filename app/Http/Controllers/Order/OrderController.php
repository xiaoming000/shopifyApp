<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;
use App\Models\OrderVariant;
use App\Models\ShopToken;
use Exception;

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
			],
			[
				'id' => 2,
				'username' => 'cc',
				'sex' => 'am',
			],
		];

		return view('order.order', $data);
		
	}

	public function getOrder()
	{
		//获取每个店铺的订单写入order表，同时更新variant stock
		$common = new Common();
		$api_name = 'orders';

		foreach (ShopToken::all() as $shop_token){
			try{
				$response = $common->getData($shop_token, $api_name);
				$response = json_decode($response, true);

				dd($response);

			} catch(Exception $e) {
				echo 'Message: ' . $e->getMessage();
			}
			
		}
	}
}
