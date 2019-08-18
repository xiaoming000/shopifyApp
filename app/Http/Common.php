<?php

namespace App\Http;

use App\Models\ShopToken;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    public function shopifyHttp($url,$method="get",$arr=[],$access_token=""){
       if (empty($access_token)) {
           return false;
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
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

    /**
     * 通过店铺的token获取所有订单
     *
     * @param [type] $shop_token 店铺名称
     * @param [type] $api_name API名称
     * @param string $method 请求方法
     * @param array $arr 请求参数
     * @return string 请求回应
     * @author dengweixiong
     */
    public function getData($shop_token, $api_name, $method="get", $arr=[]){
        $access_token = $shop_token->access_token;
        $url = 'https://' . $shop_token->shop . '/admin/api/2019-07/' . $api_name . '.json';

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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr));
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
    

    /**
     * 本地发送邮件模块
     *
     * @param [type] $shop_name 店铺名称
     * @param [type] $email 邮件地址
     * @return boolean
     * @author dengweixiong
     */
    public function sendMail($shop_name, $email)
    {
        if (empty($shop_name) or empty($email)){
            return false;
        }

        // Load Composer's autoloader
        // require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.qq.com';                    // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '861579607@qq.com';             // SMTP username
            $mail->Password   = 'slkzudajjzsjbcch';                     // SMTP password
            $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 465;                                    // TCP port to connect to
            $mail->CharSet    = 'UTF-8';

            //Recipients
            $mail->setFrom('861579607@qq.com', $shop_name);
            // $mail->addAddress('dengweixiong@sailvan.com', '839948469');     // Add a recipient
            $mail->addAddress($email, $email);     // Add a recipient

            // Attachments 附件
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'shipping imformation';
            $mail->Body    = 'Your goods have been shipped      ---' . $shop_name;
            // $mail->AltBody = 'test This is the body in plain text for non-HTML mail clients';

            $mail->send();
            // echo 'Message has been sent';

            return true;
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }

    }

    /**
     * php post请求
     *
     * @param [type] $url
     * @param array $data
     * @param array $header
     * @param integer $timeout
     * @return string
     * @author dengweixiong
     */
    public function doCurlPostRequest($url, $data = [], $header = [], $timeout = 5){
        if($url == '' || $timeout <=0){
          return false;
        }
        
        $curl = curl_init((string)$url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_TIMEOUT,(int)$timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //添加自定义的http header

        return curl_exec($curl);
    }

}