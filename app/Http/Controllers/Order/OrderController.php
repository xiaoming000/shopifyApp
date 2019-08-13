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
		
		
	}

	/**接口测试获取数据 */
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
							$order_instance = Order::create([
								'shopify_id' => $order['id'],
								'shop_token_id' => $shop_token->id,
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
}
