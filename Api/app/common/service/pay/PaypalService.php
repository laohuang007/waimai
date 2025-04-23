<?php


namespace app\common\service\pay;


use think\facade\Log;
use think\facade\Cache;
use app\common\service\FileService;

class PaypalService
{
    const PAYPAL_API_TOKEN = 'paypal:token';
    protected $api;
    private $clientId;
    private $secret;
    
    
    public function __construct($clientId,$secret){
            // api地址
            // 正式：https://api-m.paypal.com
            // 测试：https://api-m.sandbox.paypal.com
            $this->api = "https://api-m.sandbox.paypal.com";
            $this->clientId=$clientId;
            $this->secret=$secret;
    }
    /**
    * 获取token，一般返回来的access token有效时是9个小时
    **/
    public function getToken()
    {
        try {
            // 获取缓存中是否有存在
            $tokens = Cache::get(self::PAYPAL_API_TOKEN);
            if ($tokens != null) {
               // 有存在直接返回 access_token
                $array = json_decode($tokens, true);
                return resultArray(1, 'SUCCESS', [
                    'access_token' => $array['access_token']
                ]);
            }
            // 使用测试pay
            $clientId = $this->clientId;
            $clientSecret = $this->secret;
            // 对凭证base64编码
            $credentials = base64_encode($clientId . ':' . $clientSecret);
            $url =  $this->api . '/v1/oauth2/token';

            $curl_ch = curl_init();
            curl_setopt($curl_ch, CURLOPT_URL, $url);
            curl_setopt($curl_ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_ch, CURLOPT_POST, 1);
            curl_setopt($curl_ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($curl_ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $credentials
            ));


            $result = curl_exec($curl_ch);
            if (curl_errno($curl_ch)) {
                return resultArray(0, curl_error($curl_ch));
            }
            curl_close($curl_ch);
            // 获取成功写入缓存
            $array = json_decode($result, true);
            $flag = Cache::set(self::PAYPAL_API_TOKEN, $result, $array['expires_in']);
            if ($flag) {
                return resultArray(1, 'SUCCESS', [
                    'access_token' => $array['access_token']
                ]);
            }
        } catch (\Exception $e) {
            return resultArray(0, "获取token失败=>".$e);
        }
        return resultArray(0, '获取token失败');
    }
    
    public function posturl($url,$data=[]){
            $data  = json_encode($data,JSON_UNESCAPED_UNICODE);   
            $tokens = $this->getToken();
            if ($tokens['code'] != 1) {
                return resultArray(0, $tokens['msg']);
            }
            $token = $tokens['data']['access_token'];
            
            $headerArray =array("Content-Type: application/json",'Authorization: Bearer ' . $token);
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$headerArray);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            // 设置超时时间（单位：秒）
            $timeout = 10; // 设置为10秒
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            $output = curl_exec($curl);
            curl_close($curl);
            // $output = json_decode($output,true);
            return $output;
    }
    /**
     * curl 请求 todo ①注释是access-token方式 ②是base64加密凭证
     * @param string $url
     * @param array $data
     * @param string $method
     * @return array
     */
    public function curl(string $url, array $data = [], string $method = 'GET'): array
    {
        try {
            $payload = empty($data) ? '' : json_encode($data);

            /*
             *  todo ① Access-Token方式，我的是放缓存，需要从缓存获取，缓存没有话会重新去请求拉取*/
            $tokens = $this->getToken();
            if ($tokens['code'] != 1) {
                return resultArray(0, $tokens['msg']);
            }
            $token = $tokens['data']['access_token'];
            

            // todo ② 凭证编码
            // $token = base64_encode($this->clientId . ':' . $this->secret);

            $curl_ch = curl_init();
            curl_setopt($curl_ch, CURLOPT_URL, $url);
            // 将凭证放入请求头
            curl_setopt($curl_ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                /* todo ①  Access-Token方式请求头 */
                'Authorization: Bearer ' . $token
               
                // todo ② 直接使用base64加密凭证
                // 'Authorization: Basic ' . $token
            ));
            curl_setopt($curl_ch, CURLOPT_RETURNTRANSFER, 1);
            if ($method === 'POST') {
                curl_setopt($curl_ch, CURLOPT_POST, true);
                curl_setopt($curl_ch, CURLOPT_POSTFIELDS, $payload);
            } elseif ($method === 'GET') {
                curl_setopt($curl_ch, CURLOPT_HTTPGET, true);
            }

            $result = curl_exec($curl_ch);
            if (curl_errno($curl_ch)) {
                return resultArray(0, curl_error($curl_ch));
            }

            $data = json_decode($result, true);
            $httpStatusCode = curl_getinfo($curl_ch, CURLINFO_HTTP_CODE);
            if ($httpStatusCode == 422) {
                $message = $data['message'] ?? "";
                return resultArray(0, 'HTTP 422 Unprocessable entity ERROR:' . $message);
            } else if ($httpStatusCode == 504) {
                return resultArray(0, 'HTTP 504 Gateway Timeout');
            }

            curl_close($curl_ch);
            return resultArray(1, '', $data);
        } catch (\Exception $e) {
            return resultArray(0, "请求报错=>".$e->getMessage());
        }
    }
    // 下单
    public function create($order_sn,$order_name,$fee_type,$fee,$token="",$url="")
    {
        // OrderId是我的自定义订单号
        if(empty($url)){
            $url=FileService::getFileUrl();
        }
        $successUrl = $url.'/api/order/notify?type=1&OrderId=' . $order_sn . '&PtToken='. $token;
        $cancelUrl = $url.'/api/order/notify?type=2&OrderId=' . $order_sn . '&PtToken='. $token;
      
        // 创建订单
        $url = $this->api . "/v2/checkout/orders";
        $data = array(
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => "$order_sn",
                    'description' => "$order_name",
                    'amount' => [
                       // 货币缩写美元
                        'currency_code' => $fee_type??'USD',
                        // 金额支持小数后两位
                        'value' =>$fee
                    ]
                ]
            ],
            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING',
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]
        );
        // return $data;
        // 调用上面封装的curl
        // $result = $this->posturl($url, $data);
        $result = $this->curl($url, $data, 'POST');
        if ($result['code'] == 1) {
            // 下单成功有返回一个id需要记录，后续跳转到成功支付页面，用户确认订单信息的时候需要通过这个id做捕获订单付款，不然不会扣除用户账户信息
            // 下单成功之后直接跳转到支付链接（links数组中的第二个是支付页面的链接）
            $links = $result['data']['links'][1]['href'];
            // header("Location: {$links}");
            // exit(0);
        }
        return $result;
    }
    
    // 获取订单信息
    public function getorder($url)
    {
        if(empty($url)){
            return false;
        }
        $result = $this->curl($url);
        if ($result['code'] == 1) {
            // 下单成功有返回一个id需要记录，后续跳转到成功支付页面，用户确认订单信息的时候需要通过这个id做捕获订单付款，不然不会扣除用户账户信息
            // 下单成功之后直接跳转到支付链接（links数组中的第二个是支付页面的链接）
            // $links = $result['data']['links'][1]['href'];
            // header("Location: {$links}");
            // exit(0);
        }
        return $result;
    } 

}