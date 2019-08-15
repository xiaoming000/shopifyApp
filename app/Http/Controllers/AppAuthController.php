<?php

namespace App\Http\Controllers;

use App\Models\ShopToken;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class AppAuthController
 * @package App\Http\Controllers
 * @author xiaoxiaoming @date 2019/8/14
 */
class AppAuthController extends Controller
{

	public function index(Request $request){
		return redirect('login');
	}

    /**
     * 商店授权认证
     * @author xiaoxiaoming
     * @date 2019/8/14
     */
	public function confirmInstall(Request $request){
		$res  = $request->input();
		// $jsonStr = '{"code":"614b1ec66d025b39ee0b65b556595c00","hmac":"fc0378710f7b088d059d3e75b82b53ceb9f92361becb01ec47c056ed0d5d1f45","shop":"xn-4gq48l9y6ap6sf0q.myshopify.com","state":"test","timestamp":"1564664962"}';
		// $res = json_decode($jsonStr,true);
		// 日志写入
		$logs  = date("Y-m-d H:i:s").":\n";
		$logs .= json_encode($res);
        file_put_contents(storage_path().'/logs/confirmInstall.log', $logs."\n",FILE_APPEND);
        // 交换永久令牌 tip：安全验证暂时未做，需补充
        // https://{shop}.myshopify.com/admin/oauth/access_token
        $shop  = $res['shop'];
        $code  =  $res['code'];
        $url   = "https://".$shop."/admin/oauth/access_token";
        $url  .= "?client_id=ad95aafec51fd0c3f75287b1b1dc39a1";
        $url  .= "&client_secret=6075a27106ea0d9ad3ac888cd4cd6521";
        $url  .= "&code=".$code;
        $param = array(
        	'client_id'     =>env('CLIENT_ID'),
        	'client_secret' =>env('CLIENT_secret'),
        	'code'          =>$code
        );
        $param    = json_encode($param);
        $Common   = new Common();
        $respomse = $Common->http_curl($url,'post','json',$param);
        $respomse = json_decode($respomse,true);
        if (!$respomse) {
			$logs  = date("Y-m-d H:i:s").":\n";
			$logs .= $shop."确认结果返回为空";
	        file_put_contents(storage_path().'/logs/confirmInstall.log', $logs."\n",FILE_APPEND);
	        echo $logs;
	        exit();
        }
 		$token  =  $respomse['access_token'];
 		$scope  =  $respomse['scope'];
 		$dbInsert = DB::table('shop_token')->where('shop',$shop)->first();
 		if ($dbInsert) {
	 		$update =  [
	 			'access_token' => $token,
	 			'scope'       => $scope,
	 			'update_time' => time(),
	 			'is_delete'   => 0
	 		];
	 		$dbUpdate = DB::table('shop_token')->where('shop',$shop)->update($update);
	 		if (!$dbUpdate) {
	 			$logs  = date("Y-m-d H:i:s").":\n";
	 			$logs .= json_encode($update)." 数据库操作失败\n";
		        file_put_contents(storage_path().'/logs/AppAuth/db.log', $logs."\n",FILE_APPEND);
	 		}	 		  			
 		}else{
	 		$insert =  [
                'userid'=>Auth::id(),
	 			'shop'=>$shop,
	 			'access_token' => $token,
	 			'scope'       => $scope,
	 			'add_time'    => time(),
	 			'update_time' => time(),
	 			'is_delete'   => 0
	 		]; 
	 		$dbInsert = DB::table('shop_token')->insert($insert);
	 		if (!$dbInsert) {
	 			$logs  = date("Y-m-d H:i:s").":\n";
	 			$logs .= json_encode($insert)." 数据库操作失败\n";
		        file_put_contents(storage_path().'/logs/AppAuth/db.log', $logs."\n",FILE_APPEND);
		        echo "系统错误！";exit();
	 		}
 		}
 		// 设置商店店铺名
        $this->setShopName($shop);
        return redirect("home");
	}

    /**
     * @param Request $request
     * @return bool|Factory|RedirectResponse|View
     * @author xiaoxiaoming
     * @date 2019/8/14
     * 用户商店添加
     */
	public function registerShop(Request $request){
//	    dd($request->method());
        if ($request->method() == "GET"){
            return view('register_shop');
        }else{
            $shop = $request->input('shop_name');
            // 跳转https://{shop}.myshopify.com/admin/oauth/authorize?client_id={api_key}&scope={scopes}&redirect_uri={redirect_uri}&state={nonce}&grant_options[]={access_mode}
            if ($shop) {
                $shop = $shop.".myshopify.com";
                $api_key = env('CLIENT_ID');
                $scopes  = "read_orders,write_orders,read_customers,write_products,read_products";
                $redirectUrl = "https://shopify.xiaoxiaoming.net/index.php/confirmInstall";
                $sendUrl = "https://".$shop."/admin/oauth/authorize?client_id=".$api_key."&scope=".$scopes."&redirect_uri=".$redirectUrl."&state=test";
                return redirect()->away($sendUrl);
            }else{
                echo "请求参数不完整！";
                return false;
            }
        }
    }

    /**
     * 获取用户的店铺名
     * @param $shop
     * @return bool
     */
    public function setShopName($shop){
        $shopRequestUrl = 'https://' . $shop . '/admin/api/2019-07/shop.json';
        $common = new Common();
        $shopInfo = new ShopToken();
        $access_token = $shopInfo->getTokenByShop($shop);
        $result = $common -> shopifyHttp($shopRequestUrl,'get',[],$access_token);
        $result = json_decode($result,true);
        if (!isset($result['shop']['name'])){
            echo "接口请求错误！";
            return false;
        }
        $shop_name = $result['shop']['name'];
        $shopInfo->updateShop([['shop','=',$shop]],['shop_name'=>$shop_name]);
        return true;
    }

}
