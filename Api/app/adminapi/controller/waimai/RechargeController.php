<?php

namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\RechargeLists;
use app\adminapi\validate\waimai\RechargeValidate;
use app\common\model\waimai\{Recharge};
use app\common\service\FileService;
use app\common\service\ConfigService;
use app\common\service\MoneyService;
/**
 * 订单管理控制器
 * Class RechargeController
 * @package app\adminapi\controller\article
 */
class RechargeController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new RechargeLists());
    }
    
    // 新增
    public function add()
    {
        $params = (new RechargeValidate())->post()->goCheck('add');
        try {
            $order_sn=generate_sn(Recharge::class, 'order_sn','CZ');
            $ip=getClientIP();
            Recharge::create([
                'order_sn' => $order_sn,
                'uid' => $this->adminId,
                'pay_type' => $params['pay_type']??1,
                'status' => 0,
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'order_amount' => $params['order_amount']??0,
                'ip' => $ip??"",
                "create_by"=>$this->adminId,
                "update_by"=>$this->adminId
            ]);
            return $this->success('添加成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }
    // 审核订单
    public function updateStatus()
    {
        $params = (new RechargeValidate())->post()->goCheck('updateStatus');
        $Model=Recharge::where(['id' =>$params['id']])->findOrEmpty();
        if($Model->isEmpty()){
             return $this->fail('订单不存在');
        }
        if($Model->status!=0){
            return $this->fail('订单状态已经改变了,不可以进行操作了');
        }
        try {
            $service_charge=0;
            $reality_amount=0;
            $order_amount=floatval($Model->order_amount);
            $uid=$Model->uid;
            $rate=floatval($params['rate'])??0;
            $remark=$params['remark']??"";
            if($params['status']==1){
                $service_charge=$order_amount*$rate;
                $reality_amount=$order_amount-$service_charge;
                $res=MoneyService::adminMoney($Model->uid, 1, 1,$reality_amount,$Model->order_sn,  $remark,$this->adminId);
                if ($res["code"]!=1) {
                    return $this->fail($res["msg"]);
                }
            }
            Recharge::update([
                'id' => $params['id'],
                'status' => $params['status'],
                'pay_time'=>time(),
                'rate'=>$rate,
                'service_charge'=>$service_charge,
                'reality_amount'=>$reality_amount,
                'remark'=>$remark,
                "update_by"=>$this->adminId
            ]);
            return $this->success('修改成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }
    
    // 删除订单
     public function delete()
    {
        $params = (new RechargeValidate())->post()->goCheck('delete');
        Recharge::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        Recharge::destroy($params['id']);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes  资讯分类详情
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:54
     */
    public function detail()
    {
        $params = (new RechargeValidate())->goCheck('detail');
        $result = Recharge::findOrEmpty($params['id'])->toArray();
        return $this->data($result);
    }




}