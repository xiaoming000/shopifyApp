<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Common;

class AppAuthController extends Controller
{
    
	public function index(Request $request){
		$hmac = $request->get('hmac');
		$shop = $request->get('shop');
		$hmac = $request->get('timestamp');
		// 跳转https://{shop}.myshopify.com/admin/oauth/authorize?client_id={api_key}&scope={scopes}&redirect_uri={redirect_uri}&state={nonce}&grant_options[]={access_mode}
		if ($shop) {
			$api_key = "ad95aafec51fd0c3f75287b1b1dc39a1";
			$scopes  = "write_orders,read_customers";
			$redirectUrl = "http://shopify.xiaoxiaoming.net/index.php/confirmInstall";
			$sendUrl = "https://".$shop."/admin/oauth/authorize?client_id=".$api_key."&scope=".$scopes."&redirect_uri=".$redirectUrl."&state=test";
			return redirect()->away($sendUrl);
		}else{
			echo "请求参数不完整！";
		}
	}

	public function confirmInstall(Request $request){
		$res  = $request->input();
		// $jsonStr = '{"code":"614b1ec66d025b39ee0b65b556595c00","hmac":"fc0378710f7b088d059d3e75b82b53ceb9f92361becb01ec47c056ed0d5d1f45","shop":"xn-4gq48l9y6ap6sf0q.myshopify.com","state":"test","timestamp":"1564664962"}';
		// $res = json_decode($jsonStr,true);
		// 日志写入
		$logs = date("Y-md-d H:i:s").":\n";
		$logs .= json_encode($res);
        file_put_contents(storage_path().'/logs/confirmInstall.log', $logs."\n",FILE_APPEND);
        // 交换永久令牌 tip：安全验证暂时未做，需补充
        // https://{shop}.myshopify.com/admin/oauth/access_token
        $shop = $res['shop'];
        $code =  $res['code'];
        $url  = "https://".$shop."/admin/oauth/access_token";
        $url  .= "?client_id=ad95aafec51fd0c3f75287b1b1dc39a1";
        $url  .= "&6075a27106ea0d9ad3ac888cd4cd6521";
        $url  .= "&code=".$code;
        $param = array(
        	'client_id'=>'ad95aafec51fd0c3f75287b1b1dc39a1',
        	'client_secret'=>'6075a27106ea0d9ad3ac888cd4cd6521',
        	'code'=>$code
        );
        $param = json_encode($param);
        $Common = new Common();
        $respomse = $Common->http_curl($url,'post','json',$param);
        var_dump($respomse);

	}


}
