<?php

namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\WithdrawLists;
use app\adminapi\validate\waimai\WithdrawValidate;
use app\common\model\waimai\{Withdraw};
use app\common\service\FileService;
use app\common\service\ConfigService;
use app\common\service\MoneyService;
/**
 * 订单管理控制器
 * Class WithdrawController
 * @package app\adminapi\controller\article
 */
class WithdrawController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new WithdrawLists());
    }
    
    // 新增
    public function add()
    {
        $params = (new WithdrawValidate())->post()->goCheck('add');
        try {
            $order_sn=generate_sn(Withdraw::class, 'order_sn','TX');
            $ip=getClientIP();
            $order_amount=floatval($params['order_amount'])??0;
            $res=MoneyService::adminMoney($this->adminId, 2, 2,$order_amount,$order_sn,"提现扣除",$this->adminId);
            if ($res["code"]!=1) {
                return $this->fail($res["msg"]);
            }
            Withdraw::create([
                'order_sn' => $order_sn,
                'uid' => $this->adminId,
                'pay_type' => $params['pay_type']??1,
                'status' => 0,
                'order_amount' => $order_amount,
                'wallet_address' => $params['wallet_address']??'',
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
        $params = (new WithdrawValidate())->post()->goCheck('updateStatus');
        $Model=Withdraw::where(['id' =>$params['id']])->findOrEmpty();
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
            $service_charge=$order_amount*$rate;
            $reality_amount=$order_amount-$service_charge;
            if($params['status']==2){
                $res=MoneyService::adminMoney($Model->uid, 2, 1,$order_amount,$Model->order_sn, "提现审核失败,退回余额",$this->adminId);
                if ($res["code"]!=1) {
                    return $this->fail($res["msg"]);
                }
                $reality_amount=0;
                $service_charge=0;
                $rate=0;
            }
            Withdraw::update([
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
        $params = (new WithdrawValidate())->post()->goCheck('delete');
        Withdraw::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        Withdraw::destroy($params['id']);
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
        $params = (new WithdrawValidate())->goCheck('detail');
        $result = Withdraw::findOrEmpty($params['id'])->toArray();
        return $this->data($result);
    }




}