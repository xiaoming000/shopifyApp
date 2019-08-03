<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShopToken;

class OrderController extends Controller
{    
	public function index(){
		$shop = 'xn-4gq48l9y6ap6sf0q.myshopify.com';
		$ShopToken = new ShopToken();
		$token = $ShopToken->getTokenByShop($shop);
		var_dump($token);
	}

}
