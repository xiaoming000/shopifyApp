<?php

namespace App\Http;
use App\Models\ShopToken;

/**
 *  公用方法 @auther xiaoxiaoming @date 2019/08/01
 */
class Common
{
	
	public function http_curl($url, $method='get',$res='json',$arr=''){

        // 初始化curl
        $ch = curl_init();
        // 设置curl参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        // 采集
        $output = curl_exec($ch);
        $error = curl_error($ch);
        // 关闭
        curl_close($ch);
        if ($res = 'json') {
            if ($error) {
                // 请求发生错误，返回错误信息
                return $error;
            }else{
                // 请求正确返回结果
                // return json_decode($output, true);
                return $output;
            }       
        }
	}


    // shopify,http_curl请求 通过session自动设置access_token请求头
    public function shopifyHttp($url,$method="get",$arr="",$access_token=""){
        if (empty($access_token)) {
        //    $shop = session("shop");
           $shop = "xn-4gq48l9y6ap6sf0q.myshopify.com";
           $shopToken = new ShopToken();
           $access_token = $shopToken -> getTokenByShop($shop);
           if (empty($access_token)) {
               return false;
           }
        }
        $header[] = "X-Shopify-Access-Token: ".$access_token;

        // 初始化curl
        $ch = curl_init();
        // 设置curl参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        // 采集
        $output = curl_exec($ch);
        $error = curl_error($ch);
        // 关闭
        curl_close($ch);
        if ($error) {
                // 请求发生错误，返回错误信息
                return $error;
            }else{
                // 请求正确返回结果
                // return json_decode($output, true);
                return $output;
            }      

    }

}