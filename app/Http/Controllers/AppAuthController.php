<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppAuthController extends Controller
{
    
	public function index(Request $request){
		$hmac = $request->get('hmac');
		$shop = $request->get('shop');
		$hmac = $request->get('timestamp');
		echo $shop."\n";
		// 跳转https://{shop}.myshopify.com/admin/oauth/authorize?client_id={api_key}&scope={scopes}&redirect_uri={redirect_uri}&state={nonce}&grant_options[]={access_mode}
		// sleep(3);
		if ($shop) {
			$api_key = "ad95aafec51fd0c3f75287b1b1dc39a1";
			$scopes  = "write_orders,read_customers";
			$redirectUrl = "http://shopify.xiaoxiaoming.net/index.php/order";
			$sendUrl = "https://".$shop."/admin/oauth/authorize?client_id=".$api_key."&scope=".$scopes."&redirect_uri=".$redirectUrl."&state=test";
			var_dump($sendUrl);exit();
			return redirect()->away($sendUrl);
		}else{
			echo "请求参数不完整！";
		}
	}


}
