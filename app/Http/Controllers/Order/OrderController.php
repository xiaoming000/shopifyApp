<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;
use App\Models\Order;
use App\Models\OrderTemporary;
use App\Models\OrderVariant;
use App\Models\ShopToken;
use App\Models\Variant;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * 订单任务控制器
 *
 * @author dengweixiong
 */
class OrderController extends Controller
{

	/**
	 * 获取已付款订单 
	 * 旧的静态渲染订单页面接口, 已被重写, 保留测试
	 * 旧界面: order.blade.php
	 * 新界面: order_new.blade.php
	 * */
	/*
	public function index(Request $request)
	{
		//Request filter , shop_token_id(shop_name), 订单编号, 发货状态, 

		$sessions = $request->session()->get('shops');
		if (empty($sessions)) {
			return redirect('home');
		}
		
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
				'is_cancel' => 0,
				'shopify_id' => $filter_order_id,
				])->WhereIn('shop_token_id', $shop_id)->get();
		} else {
			$orders = Order::where([
				'financial_status' => 'paid',
				'is_close' => 0,
				'is_cancel' => 0,
				])->WhereIn('shop_token_id', $shop_id)->get();
		}

		$data = [];
		foreach($orders as $k => $order) {
			$shop_token_table = ShopToken::find($order->shop_token_id);

			$data[$k] = [
				'order_id' => $order->shopify_id,
				'customer_name' => $order->name,
				'order_total_price' => $order->total_price,
				'order_is_send' => $order->is_send,
				'order_is_send_email' => $order->is_send_email,
				'order_is_close' => $order->is_close,
				'order_is_cancel' => $order->is_cancel,
				'shop_url'	=> 'http://www.' . $shop_token_table->shop,
				'shop_name'	=> $shop_token_table->shop_name,
				'order_tracking_num' => $order->tracking_num,
				];
			
			$order_variants = OrderVariant::where(['order_id' => $order->id])->get();
			$goods = [];
			foreach($order_variants as $order_variant) {
				$variant = Variant::where(['shopify_variant_id' => $order_variant->variant_id])->first();
				if (isset($variant)){

					$goods[] = [
						'title' => $variant->title ?? 'no title',
						'url' => $variant->ali_item_url,
						'quantity' => $order_variant->quantity,
					];
				}
			}
			$data[$k]['goods'] = $goods;
		}
		
		// dd($data);

		return view('order.order', ['data' => $data]);
	}
	*/
	

	/**
	 * 已付款订单界面
	 *
	 * @author dengweixiong
	 */
	public function index(Request $request)
	{

		$sessions = $request->session()->get('shops');
		if (empty($sessions)) {
			return redirect('home');
		}

		return view('order.order_new');
	}

	/**
	 * 已付款订单接口
	 * 异步渲染请求数据
	 *
	 * @author dengweixiong
	 */
	public function paid(Request $request) {

		// 选择进入店铺token
		$sessions = $request->session()->get('shops');
		if (empty($sessions)) {
			return redirect('home');
		}
		
		$sessions = json_decode($sessions,true);

		$shop_id = [];
		foreach ($sessions as $session) {
			$shop_id[] = $session['shop_id'];
		}
		
		// 订单筛选条件
		$filter_data = [
			'financial_status' => 'paid',
			'is_close' => 0,
			'is_cancel' => 0,
		];

		// 筛选订单号
		$filter_order_id = $request->input('order_no');
		if (isset($filter_order_id)) {
			$filter_data['shopify_id'] = $filter_order_id;
		}

		// 筛选店铺名
		$filter_shop_name = $request->input('merchant_no');
		if (isset($filter_shop_name)) {
			$filter_data['shop_name'] = $filter_shop_name;
		}

		// 筛选时间
		$filter_data_time = [];

		$filter_created_at = $request->input('start_time');
		if (isset($filter_created_at)) {
			$filter_data_time['shopify_created_at'] = date('Y-m-d H:i:s', strtotime($filter_created_at));
		} else {
			$filter_data_time['shopify_created_at'] = date('Y-m-d H:i:s', strtotime('1970-1-1 00:00:00'));
		}

		$filter_updated_at = $request->input('end_time');
		if (isset($filter_updated_at)) {
			$filter_data_time['shopify_updated_at'] = date('Y-m-d H:i:s', strtotime($filter_updated_at));
		} else {
			$filter_data_time['shopify_updated_at'] = date('Y-m-d H:i:s', strtotime('2050-1-1 00:00:00'));
		}
		
		// 进行分页查询
		$page = $request->input('page');
		$limit = $request->input('limit');
		$start = ($page - 1) * $limit;

		// 符合订单总数
		$order_nums = OrderTemporary::where($filter_data)
				->WhereIn('shop_id', $shop_id)
				->where('shopify_created_at', '>', $filter_data_time['shopify_created_at'])
				->where('shopify_updated_at', '<', $filter_data_time['shopify_updated_at'])
				->get();

		// 分页进行查询条件记录
		$order_list = DB::table('order_temporary')
			->where($filter_data)
			->WhereIn('shop_id', $shop_id)
			->where('shopify_created_at', '>', $filter_data_time['shopify_created_at'])
			->where('shopify_updated_at', '<', $filter_data_time['shopify_updated_at'])
			->offset($start)->limit($limit)
			->get();
		
		$datas = ['code' => 0, 'msg' => ''];
		// 将总的记录条数传给前台进行渲染分页
		$datas['count'] = count($order_nums);
		// 将数据通过json格式响应给前台渲染
		$datas['data'] = $order_list;

		echo json_encode($datas);
	}


	/**
	 * 旧接口, 已从写
	 * 废弃, 保留测试
	 *
	 * @author dengweixiong
	 */
	public function getOrder()
	{
		//获取每个店铺的订单写入order表，同时更新variant stock
		$common = new Common();
		$api_name = 'orders';

		foreach (ShopToken::all() as $shop_token){
			// $shop_token = ShopToken::where(['id' => 5])->first();
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

			} catch(Exception $e) {
				echo 'Message: ' . $e->getMessage();
			}
			
		}
	}

	/**
	 * 同步shopify订单数据接口, 写入本地数据库
	 *
	 * @author dengweixiong
	 */
	public function getOrderAndTemp()
	{
		//获取每个店铺的订单写入order表，同时更新variant stock
		$common = new Common();
		$api_name = 'orders';

		foreach (ShopToken::all() as $shop_token){
			try{
				$response = $common->getData($shop_token, $api_name);				
				$response = json_decode($response, true);

				// 获取当前店铺的token存在的订单列表写入本地数据库
				if (!empty($response['orders'])) {
					foreach($response['orders'] as $order){
						$order_exist = Order::where('shopify_id', $order['id'])->first();

						// 当订单为空时才插入数据
						if (empty($order_exist)) {
							if ( empty($order['customer']['first_name']) or empty($order['customer']['last_name']) ) {
								$name = $order['email'];
							} else {
								$name = $order['customer']['first_name'] . $order['customer']['last_name'];
							}

							$shopify_created_at = date('Y-m-d H:i:s', strtotime($order['created_at']));
							$shopify_updated_at = date('Y-m-d H:i:s', strtotime($order['updated_at']));

							// 更新订单表
							$order_instance = Order::create([
								'shopify_id' => $order['id'],
								'shop_token_id' => $shop_token->id,
								'name' => $name,
								'email'	=> $order['email'],
								'phone' => $order['phone'],
								'total_price' => $order['total_price'],
								'financial_status' => $order['financial_status'],
								'shopify_created_at' => $shopify_created_at,
								'shopify_updated_at' => $shopify_updated_at,
							]);

							// 对于每一个订单购买的商品, 更新本地商品状态, 推送需要从速卖通查询的商品重新刊登
							foreach($order['line_items'] as $k => $line_item) {
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

									$recode[$k] = [
										'title' => $variant->title,
										'url'	=> $variant->ali_item_url,
										'quantity' => $line_item['quantity'],
									];
								} else {
									$recode[$k] = [
										'title' => 'no title',
										'url'	=> 'no url',
										'quantity' => $line_item['quantity'],
									];
								}

								
							}

							// 更新order临时表
							$order_temp_instance = OrderTemporary::create([
								'shopify_id' => $order['id'],
								'shop_id' => $shop_token->id,
								'shop_name' => $shop_token->shop_name,
								'shop_url' => 'http://www.' . $shop_token->shop,
								'customer_name' => $name,
								'email'	=> $order['email'],
								'phone' => $order['phone'],
								'total_price' => '$' . $order['total_price'],
								'financial_status' => $order['financial_status'],
								'shopify_created_at' => $shopify_created_at,
								'shopify_updated_at' => $shopify_updated_at,
								'goods'	=> json_encode($recode),
							]);
						}
					}
				}

			} catch(Exception $e) {
				echo 'Message: ' . $e->getMessage();
			}
			
		}
	}

	/**
	 * 订单发货与发邮件接口
	 *
	 * @author dengweixiong
	 */
	public function isSend(Request $request)
	{
		$order_id = $request->input('order_id') ?? '';
		// 保留mock跟踪号位置
		$tracking_num = $request->input('tracking_num') ?? '11111';


		$order = Order::where([
			'shopify_id' => $order_id,
		])->first();

		$order_temporary = OrderTemporary::where([
			'shopify_id' => $order_id,
		])->first();


		// 仅当订单存在时发货和邮件
		if (!empty($order) and !empty($order_temporary)) {
			if($order_temporary->is_send_email == 1) {
				return redirect('order');
			}

			// 订单表和临时订单表的发货状态更新
			$order->is_send = 1;
			$order->save();

			$order_temporary->is_send = 1;
			$order_temporary->save();

			$common = new Common();

			// 获取当前订单属于的店铺
			$shop_token = ShopToken::find($order->shop_token_id);
			$shop_name = $shop_token->shop_name;

			// 设置订单属于的店铺的shopify为已发货状态, shopify与此同时也会发邮件
			$url = 'https://' . $shop_token->shop . '/admin/api/2019-07/orders/' . $order_id . '/fulfillments.json';

			// mock shop location_id(因未写数据表关于店铺发货位置)
			if ($shop_token->id == 1) {
				$location_id = 32009388141;
			} else {
				$location_id = 19203653694;
			}

			// 请求api post参数
			$data = [
				'fulfillment' => [
					'location_id' => $location_id,
					'tracking_number' => $tracking_num,
					"notify_customer" => true,
				],
			];

			$header[] = "X-Client-ID:7e43c50781295f35";
			$header[] = "X-Shopify-Access-Token:" . $shop_token->access_token;
			$common->doCurlPostRequest($url, $data, $header);

			// 本地发送邮件模块
			if ($common->sendMail($shop_name, $order->email)){
				$order->is_send_email = 1;
				$order->tracking_num = $tracking_num;
				$order->save();

				$order_temporary->is_send_email = 1;
				$order_temporary->tracking_num = $tracking_num;
				$order_temporary->save();
			}
		}

		return redirect('order');
	}

	/**
	 * 取消订单接口
	 *
	 * @author dengweixiong
	 */
	public function isCancel(Request $request) {
		$order_id = $request->input('order_id') ?? '';

		if (empty($order_id)) {
			return redirect('order');
		}

		// 更新本地数据库订单取消状态
		$order = Order::where('shopify_id', $order_id)->first();
		$order->update(['is_cancel' => 1]);
		$order_temporary = OrderTemporary::where('shopify_id', $order_id)->first();
		$order_temporary->update(['is_cancel' => 1]);

		// 同步更新取消shopify上面订单状态
		if (!empty($order)) {
			$shop_token = ShopToken::find($order->shop_token_id);
			$url = 'https://' . $shop_token->shop . '/admin/api/2019-07/orders/' . $order_id . '/cancel.json';
			
			$common = new Common();
			$result = $common -> shopifyHttp($url, 'post', [], $shop_token->access_token);
			$result = \json_decode($result, true);
			
			// 提交失败, 回退
			if ((empty($result['order']['id']))) {
				$order->update(['is_cancel' => 0]);
				$order_temporary->update(['is_cancel' => 0]);
			}
		}

		return redirect('order');
	}

}
