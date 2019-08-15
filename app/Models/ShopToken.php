<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShopToken extends Model
{
	protected $table = "shop_token";

	public function hasManyOrder()
	{
		return $this->hasMany(Order::class);
	}
    
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

    /**
     * 获取登入用户的商店列表
     * @param array $ids
     * @return \Illuminate\Support\Collection|mixed
     */
	public function getAuthShop($ids=[]){
        $userid = Auth::id();
	    if (!empty($ids)){
            $shops = DB::table("shop_token")->whereIn('id',$ids)->where("userid",$userid)->get();
            $shops = json_decode(json_encode($shops), true);
        }else{
            $shops = DB::table("shop_token")->where("userid",$userid)->get();
            $shops = json_decode(json_encode($shops), true);
        }
        return $shops;
    }

    /**
     * 修改
     * @param $where
     * @param $update
     * @return bool
     */
    public function updateShop($where, $update){
        if (empty($where)){
            return false;
        }
        try{
            DB::table('shop_token')->where($where)->update($update);
            return true;
        }catch (\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 删除
     * @param array $shop
     * @return bool
     */
    public function  delByShop($shops=[]){
        if (empty($shops)){
            return false;
        }
        try{
            DB::table('shop_token')->whereIn('shop',$shops)->delete();
            return true;
        }catch (\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
}

