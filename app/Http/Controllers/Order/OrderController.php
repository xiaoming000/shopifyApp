<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;
use App\Models\Order;
use App\Models\OrderVariant;
use App\Models\ShopToken;
use App\Models\Variant;
use Exception;

class OrderController extends Controller
{

	/**默认index获取已付款订单 */
	public function index(Request $request)
	{
		//Request filter , shop_token_id(shop_name), 订单编号, 发货状态, 

		$sessions = $request->session()->get('shops');
		$sessions = json_decode($sessions,true);
		// dd($sessions);
		// dd($session[0]['shop_id']);
		$shop_id = [];
		foreach ($sessions as $session) {
			$shop_id[] = $session['shop_id'];
		}
		// dd($shop_id);

		$filter_order_id = $request->input('order_id') ?? '';
		// dd($filter_order_id);
		if (!empty($filter_order_id)) {
			$orders = Order::where([
				'financial_status' => 'paid',
				'is_close' => 0,
				'shopify_id' => $filter_order_id,
				])->WhereIn('shop_token_id', $shop_id)->get();
		} else {
			$orders = Order::where([
				'financial_status' => 'paid',
				'is_close' => 0,
				])->WhereIn('shop_token_id', $shop_id)->get();
		}

		$data = [];
		foreach($orders as $k => $order) {
			$data[$k] = [
				'order_id' => $order->shopify_id,
				'customer_name' => $order->name,
				'order_total_price' => $order->total_price,
				'order_is_send' => $order->is_send,
				'order_is_send_email' => $order->is_send_email,
				'order_is_close' => $order->is_close,
				'shop_name'	=> ShopToken::find($order->shop_token_id)->shop,
				];
			
			$order_variants = OrderVariant::where(['order_id' => $order->id])->get();
			$goods = [];
			foreach($order_variants as $order_variant) {
				$variant = Variant::where(['shopify_variant_id' => $order_variant->variant_id])->first();
				if (isset($variant)){
					$goods[] = [
						'title' => $variant->title,
						'url' => $variant->ali_item_url,
					];
				}
			}
			$data[$k]['goods'] = $goods;
		}
		
		// dd($data);

		return view('order.order', ['data' => $data]);
	}

	/**接口测试同步获取数据, 到时放入计划 */
	public function getOrder()
	{
		//获取每个店铺的订单写入order表，同时更新variant stock
		$common = new Common();
		$api_name = 'orders';

		foreach (ShopToken::all() as $shop_token){
			// $shop_token = ShopToken::where(['id' => 2])->first();
			// dd($shop_token);
			try{
				$response = $common->getData($shop_token, $api_name);
				
				$response = json_decode($response, true);
				// dd($response);

				if (!empty($response['orders'])) {
					foreach($response['orders'] as $order){
						$order_exist = Order::where('shopify_id', $order['id'])->first();
						// dd($order_exist);
						// \var_dump($order['created_at']);die;

						if (empty($order_exist)) {
							if ( empty($order['customer']['first_name']) or empty($order['customer']['last_name']) ) {
								$name = $order['email'];
							} else {
								$name = $order['customer']['first_name'] . $order['customer']['last_name'];
							}

							$order_instance = Order::create([
								'shopify_id' => $order['id'],
								'shop_token_id' => $shop_token->id,
								'name' => $name,
								'email'	=> $order['email'],
								'phone' => $order['phone'],
								'total_price' => $order['total_price'],
								'financial_status' => $order['financial_status'],
								'shopify_created_at' => $order['created_at'],
								'shopify_updated_at' => $order['updated_at'],
							]);

							foreach($order['line_items'] as $line_item) {
								$order_variant_instance = OrderVariant::create([
									'order_id' => $order_instance->id,
									'variant_id' => $line_item['variant_id'],
									'quantity' => $line_item['quantity'],
								]);

								$variant = Variant::where(['shopify_variant_id' => $line_item['variant_id']])->first();
								if (isset($variant)) {
									$variant->update([
										'stock' => $variant->stock - $line_item['quantity'],
										'is_update' => 1,
									]);
								}
							}
						}
					}
				}

				// dd($response);

			} catch(Exception $e) {
				echo 'Message: ' . $e->getMessage();
			}
			
		}
	}

	/**订单发货api */
	public function isSend(Request $request)
	{
		$order_id = $request->input('order_id');

		$order = Order::where([
			'shopify_id' => $order_id,
		])->first();

		if (!empty($order)) {
			$order->is_send = 1;
			$order->save();

			$common = new Common();

			$shop_name = ShopToken::find($order->shop_token_id)->shop;
			// dd($shop_name);
			if ($common->sendMail($shop_name, $order->email)){
				$order->is_send_email = 1;
				$order->save();
			}
		}

		return redirect('order');

	}

	public function isSendEmail(Request $request)
	{
		$order_id = $request->input('order_id');

		$order = Order::where([
			'shopify_id' => $order_id,
		])->first();

		$common = new Common();
		if ($common->sendMail(ShopToken::find($order->shop_token_id)->shop, $order->email)){
			// return view('order.order');
			// dd('ok');
			// update order is_send_email
			$order->is_send_email = 1;
			$order->save();
		} else {
			dd('failed');
		}
	}
}
