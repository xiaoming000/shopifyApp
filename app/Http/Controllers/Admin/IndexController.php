<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;

class IndexController extends Controller
{
    
	public function index(Request $request){
		// session(['shop_name' => 'xn-4gq48l9y6ap6sf0q.myshopify.com']);  // 测试使用
		$shop_name = session('shop_name');
		// 设置会话保持
		session(['shop' => $shop_name]);
		// 获取商店名
		// $shop = "xn-4gq48l9y6ap6sf0q.myshopify.com";
		$shop = session('shop_name');;
		$shopRequestUrl = 'https://' . $shop . '/admin/api/2019-07/shop.json';
		$common = new Common();
		$response = $common -> shopifyHttp($shopRequestUrl);
		$response = json_decode($response,true);
		$shop_name = $response['shop']['name'];
		
		return view('admin.admin',['shop_name'=>$shop_name]);
	}


}
