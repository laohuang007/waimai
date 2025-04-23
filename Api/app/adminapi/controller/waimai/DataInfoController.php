<?php

namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\WithdrawLists;
use app\adminapi\validate\waimai\WithdrawValidate;
use app\common\model\waimai\{Orders};
use app\common\service\FileService;
use app\common\service\ConfigService;
use app\common\service\MoneyService;
/**
 * 订单管理控制器
 * Class DataInfoController
 * @package app\adminapi\controller\article
 */
class DataInfoController extends BaseAdminController
{

    public function today()
    {
        $where = [];
        if($this->adminInfo['is_user']==1){
            $where[] =  ['uid', '=', $this->adminInfo['id']];
        }
        $cxtj=[];
        $jrsjc=strtotime(date("Y-m-d")." 00:00:00");
        $cxtj[] =['create_time','>=',$jrsjc];
        $res=[
            "time"=>date("Y-m-d H:i:s"),
            "order_num"=>Orders::where($where)->where($cxtj)->count(),
            "order_price"=>Orders::where($where)->where($cxtj)->sum("order_amount"),
            "order_cg_num"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->count(),
            "order_cg_price"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("order_amount"),
            "order_cg_sxf"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("rate"),
            "order_cg_sjje"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("reality_amount"),
            "order_sb_num"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->count(),
            "order_sb_price"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->sum("order_amount"),
            "order_wzf_num"=>Orders::where($where)->where(["status"=>"0"])->where($cxtj)->count(),
            "order_wzf_price"=>Orders::where($where)->where(["status"=>"0"])->where([$cxtj])->sum("order_amount"),
        ];
        
        return $this->data($res);
    }
    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        $params=$this->request->get();
        
        if(empty($params['time'])){
            $daynum=7;
            // $start=date("Y-m-d","-$daynum");
            $start=date('Y-m-d',strtotime(" -{$daynum} day"));
            $end=date("Y-m-d");
        }else{
            $start=$params['time'][0];
            $end=$params['time'][1];
            $daynum=floor((strtotime($params['time'][1])-strtotime($params['time'][0]))/86400);
        }
        $lists=[];
        $where = [];
        if($this->adminInfo['is_user']==1){
            $where[] =  ['uid', '=', $this->adminInfo['id']];
        }else{
            if(!empty($params['uid'])){
                $where[] =  ['uid', '=', trim($params['uid'])];
            }
        }
        if(!empty($params['payid'])){
            $where[] =  ['payid', '=', trim($params['payid'])];
        }
        if(!empty($params['gid'])){
            $where[] =  ['gid', '=', trim($params['gid'])];
        }
        if(!empty($params['payer_id'])){
            $where[] =  ['payer_id', '=', trim($params['payer_id'])];
        }
        if(!empty($params['payer_email'])){
            $where[] =  ['payer_email', '=', trim($params['payer_email'])];
        }
        
        for ($i = 1; $i <= $daynum; $i++) {
             // code...
            $dqsj=date('Y-m-d',strtotime("{$end} -{$i} day"));
            $startTime=strtotime($dqsj." 00:00:00");
            $endTime=strtotime($dqsj." 23:59:59");
            $cxtj=[];
            $cxtj[] =['create_time','>=',$startTime];
            $cxtj[] =['create_time','<=',$endTime];
            $lists[]=[
                    "time"=>$dqsj,
                    "time2"=>$cxtj,
                    "order_num"=>Orders::where($where)->where($cxtj)->count(),
                    "order_price"=>Orders::where($where)->where($cxtj)->sum("order_amount"),
                    "order_cg_num"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->count(),
                    "order_cg_price"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("order_amount"),
                    "order_cg_sxf"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("rate"),
                    "order_cg_sjje"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("reality_amount"),
                    "order_sb_num"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->count(),
                    "order_sb_price"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->sum("order_amount"),
                    "order_wzf_num"=>Orders::where($where)->where(["status"=>"0"])->where($cxtj)->count(),
                    "order_wzf_price"=>Orders::where($where)->where(["status"=>"0"])->where($cxtj)->sum("order_amount"),
            ];
        }
        $cxtj=[];
        $cxtj[] =['create_time','>=',strtotime($start." 00:00:00")];
        $cxtj[] =['create_time','<=',strtotime($end." 23:59:59")];
        $res['count']=[
            "order_num"=>Orders::where($where)->where($cxtj)->count(),
            "order_price"=>Orders::where($where)->where($cxtj)->sum("order_amount"),
            "order_cg_num"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->count(),
            "order_cg_price"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("order_amount"),
            "order_cg_sxf"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("rate"),
            "order_cg_sjje"=>Orders::where($where)->where(["status"=>"1"])->where($cxtj)->sum("reality_amount"),
            "order_sb_num"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->count(),
            "order_sb_price"=>Orders::where($where)->where(["status"=>"2"])->where($cxtj)->sum("order_amount"),
            "order_wzf_num"=>Orders::where($where)->where(["status"=>"0"])->where($cxtj)->count(),
            "order_wzf_price"=>Orders::where($where)->where(["status"=>"0"])->where($cxtj)->sum("order_amount"),
        ];
        $res["where"]=$where;
        $res["list"]=$lists;
        
        return $this->data($res);
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