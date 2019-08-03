<?php

namespace App\Http;

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

}