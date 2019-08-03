<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopToken extends Model
{
	protected $table = "shop_token";
    
	public function getTokenByShop($shop){

		$tokenInfo = DB::table("shop_token")->where("shop",$shop)->first();
		$tokenInfo = json_decode(json_encode($tokenInfo), true);
		// if ($tokenInfo) {
		// 	$tokenInfo = $tokenInfo->toArray();
		// }else{
		// 	return false;
		// }
		return $tokenInfo['access_token'];
	}

}

