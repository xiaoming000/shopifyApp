<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    
	public function index(Request $request){
		$res = $request->get('shop');
        file_put_contents(storage_path().'/logs/orderTest.log', $res."\n",FILE_APPEND);
		return view('order/order');

	}

}
