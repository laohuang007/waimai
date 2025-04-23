<?php

namespace app\api\controller;


use app\common\service\ConfigService;
use app\common\model\waimai\{Goods,UserGoods,Paypal,PaypalUser,Orders};
use app\common\service\FileService;
use app\common\service\pay\PaypalService;
use think\response\Json;
use app\common\service\MoneyService;

/**
 * index
 * Class IndexController
 * @package app\api\controller
 */
class OrderController extends BaseApiController
{


    public array $notNeedLogin = ['index', 'config', 'details', 'paypal','notify'];


    /**
     * @notes 获取配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public function index()
    {
        // $result = IndexLogic::getIndexData();
        $Model=Orders::where(["order_sn"=>'paypal202504181621318924'])->with(['paypal'])->findOrEmpty();
        return $this->data([$Model]);
        // $pay=new PaypalService("AbIbg2gsr8J-bZM4_j6aYPDTPE2AMASCzA7qOz3KvDdT2kJwg2c9_18zMjYa64XO97QOgS5XvxfO6vte","EPTsDhmTRwOQ0nd3Y0E2TyU9tv6ZVX8n6JFWBr95vDQbY0nr_whG8t9pXk_sS7LtUf7cyHNl073PVTc7");
        // $token=$pay->getToken();
        return $this->data([$token]);
    }
    /**
     * @notes 获取配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public function notify()
    {
        $prefix="notify";
        $params=input();
        addLog($prefix, 1,[$params],"传入原始参数");
        $type=input('type',2);
        $OrderId=input('OrderId','');
        $PtToken=input('PtToken','');
        $token=input('token','');
        $PayerID=input('PayerID','');
        $urlid="";
        $Model="";
        $orderToken="";
        $updata="";
        if(!empty($PtToken)&&!empty($OrderId)){
            $Model=Orders::where(["order_sn"=>$OrderId])->with(['goods'])->findOrEmpty();
            if(!$Model->isEmpty()){
                $urlid=$Model['random'];
                $orderToken=md5($Model['order_sn'].$Model['random'].$Model['random'].$Model['create_time']);
                if($orderToken===$PtToken){
                    $updata=[
                        "status"=>$type,
                        "payer_token"=>$token,
                        "payer_id"=>$PayerID,
                        "pay_time"=>time(),
                        "status_time"=>time()
                    ];
                    if($type==1){
                        $PaypalModel=$Model['paypal'];
                        $pay=new PaypalService($PaypalModel['client_id'],$PaypalModel['secret_key']);
                        $getres=$pay->getorder($Model['paypal_data']['links'][0]['href']);
                        if($getres['code']==1){
                            $getdata=$getres['data'];
                            $updata["payer_data"]=$getdata;
                            $updata["payer_email"]=$getdata["payer"]["email_address"];
                            $updata["payer_name"]=$getdata["payer"]["name"]["given_name"]." · ".$getdata["payer"]["name"]["surname"];
                        }else{
                            addLog($prefix, 0,[$getres],"查询PayPal订单失败");
                        }
                        $change_amount=floatval($Model->reality_amount);
                        $remark="PayPal收款增加余额";
                        $moneyres=MoneyService::adminMoney($Model->uid, 3, 1,$change_amount,$Model->order_sn,  $remark,0);
                        addLog($prefix, 0,[$moneyres],"余额增加返回");
                    }else{
                        addLog($prefix, 0,[$type],"订单失败");
                    }
                   $Model->save($updata);
                }else{
                    addLog($prefix, 0,[$orderToken,$PtToken],"token有误");
                }
            }else{
                 addLog($prefix, 0,[$Model],"订单查询为空");
            }
        }else{
            addLog($prefix, 0,[$PtToken,$OrderId],"判断条件失败");
        }
        
        $payurl=ConfigService::get('website', 'shop_url', "https://pay.pay.ttcni.top");
        // $url=$payurl.'/#/notify/'.$type.'/'.$urlid;
        $url=$payurl.'/#/notify/'.$type;
        $result=[$url,$orderToken,$params,$updata,$Model];
        addLog($prefix, 2,$result,"结束");
        header("Location: $url"); exit(); 
        return $this->data($result);
        // $result = IndexLogic::getIndexData();
        // return $this->data($result);
    }

    /**
     * @notes 全局配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:41
     */
    public function config()
    {
        $result = [
            'shop_name' => ConfigService::get('website', 'shop_name'),
            'shop_url' => ConfigService::get('website', 'shop_url'),
            'shop_text_pay' => ConfigService::get('website', 'shop_text_pay','支付方式'),
            'shop_text_submit' => ConfigService::get('website', 'shop_text_submit','立即支付'),
            'shop_text_nopay' => ConfigService::get('website', 'shop_text_nopay','请选择支付方式?'),
            'shop_text_payin' => ConfigService::get('website', 'shop_text_payin','跳转支付中...'),
            'shop_text_payfail' => ConfigService::get('website', 'shop_text_payfail','支付失败...'),
            'shop_text_paysuccess' => ConfigService::get('website', 'shop_text_paysuccess','支付成功...'),
            'shop_text_paynot' => ConfigService::get('website', 'shop_text_paynot','没有数据'),
            'shop_color' => ConfigService::get('website', 'shop_color','#000'),
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),
            'favicon' => FileService::getFileUrl(ConfigService::get('website', 'h5_favicon')),
        ];
        return $this->data($result);
    }


    /**
     * @notes 政策协议
     * @return Json
     * @author 段誉
     * @date 2022/9/20 20:00
     */
    public function details()
    {
        $random= $this->request->post('random/s', '');
        if(empty($random)){
            return $this->fail('Not Request Data');
        }
        
        $result = Orders::field("status,amount,create_time,fee_type,image,name,order_amount,id,payid,uid,order_sn,pay_url,update_time")->append(["PaypalUser"])->where(['random' => $random])->findOrEmpty();
        if($result->isEmpty()){
                 return $this->fail('Not Order');
        }
        if($result->status>=1){
            return $this->fail('The order has expired.');
        }
        $res=$result->toArray();
        $PaypalUser=$res['PaypalUser'][0]??[];
        $res["pay_list"]=[
            ["name"=>$PaypalUser['name']??"","image"=>$PaypalUser['image']??"","id"=>$PaypalUser['id']??""]
        ];
        unset($res['PaypalUser']);
        return $this->data($res);
        // return $this->success('获取成功', $result);
    }


    /**
     * @notes 支付
     * @return Json
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public function paypal()
    {
        $id = $this->request->post('id/d');
        $payid = $this->request->post('payid/d');
        if(empty($id)||empty($payid)){
            return $this->fail('Not Request Data');
        }
        $orderdata = Orders::findOrEmpty($id);
        if($orderdata->isEmpty()){
                 return $this->fail('Not Order');
        }
        $uid=$orderdata['uid']??"";
        $pid=$orderdata['payid']??"";
        $paypaluser=PaypalUser::where(["payid"=>$pid,"uid"=>$uid])->with(['paypal'])->findOrEmpty();
        if($paypaluser->isEmpty()){
                 return $this->fail('Not Pay Type');
        }
        if($paypaluser['status']!=1){
            return $this->fail("Close Pay ");
        }
        if($paypaluser['paypal']['status']!=1){
            return $this->fail("Close Paypal");
        }
        $order_sn=$orderdata['order_sn'];
        $order_name=$orderdata['name'];
        $fee_type=$orderdata['fee_type'];
        $fee=floatval($orderdata['order_amount']);
        $rate=floatval($paypaluser['rate'])*$fee;
        $reality_amount=$fee-$rate;
        $token=md5($order_sn.$orderdata['random'].$orderdata['random'].$orderdata['create_time']);
        $pay=new PaypalService($paypaluser['paypal']['client_id'],$paypaluser['paypal']['secret_key']);
        $res=$pay->create($order_sn,$order_name,$fee_type,$fee,$token);
        if($res['code']==1){
            $url=$res['data']['links'][1]['href']??"";
            Orders::update([
                'id' => $id,
                'paypal_id' => $res['data']['id']??"",
                'pay_url' => $url,
                'paypal_data' =>$res['data'],
                'rate'=>$rate,
                'reality_amount'=>$reality_amount
            ]);
            return $this->data(["url"=>$url]);
        }
        return $this->fail("paypal fail");
    }


}