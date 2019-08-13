<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Common;

class IndexController extends Controller
{
    
	public function index(Request $request){
		// session(['shop_name' => 'xn-4gq48l9y6ap6sf0q.myshopify.com']);  // 测试使用
//		$shop_name = session('shop_name');
//		// 设置会话保持
//		session(['shop' => $shop_name]);
//		// 获取商店名
//		// $shop = "xn-4gq48l9y6ap6sf0q.myshopify.com";
//		$shop = session('shop_name');;
//		$shopRequestUrl = 'https://' . $shop . '/admin/api/2019-07/shop.json';
//		$common = new Common();
//		$response = $common -> shopifyHttp($shopRequestUrl);
//		$response = json_decode($response,true);
//		$shop_name = $response['shop']['name'];
        $shops = json_decode(session('shops'),true);
		return view('admin.admin',['shops'=>$shops]);
	}

    /**
     * @param $id
     * @author xiaoxiaoming
     * @desc 用户选择店铺
     * @date 2019/8/13
     */
	public function shop($id){
        $shopToken = new ShopToken();
	    if ($id=='0'){
            $shopInfo = $shopToken->getAuthShop();
            $shops = [];
            foreach ($shopInfo as $shop){
                $shop_id    = $shop['id'];
                $shop_name  = $shop['shop'];
                $shop_token = $shop['access_token'];
                $shop = ['shop_id'=>$shop_id,'shop_name' => $shop_name,'shop_token'=>$shop_token];
                $shops[] = $shop;
            }
        }else{
            $shopInfo = $shopToken->getAuthShop([$id]);
            $shop_name = $shopInfo[0]['shop'];
            $shop_token = $shopInfo[0]['access_token'];
            $shops = [['shop_id'=>$id,'shop_name' => $shop_name,'shop_token'=>$shop_token]];
            session(['shops' => json_encode($shops)]);
        }
        session(['shops' => json_encode($shops)]);
	    return redirect('admin');
    }

}
