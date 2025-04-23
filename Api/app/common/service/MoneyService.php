<?php


namespace app\common\service;


use Exception;
use app\common\model\auth\Admin;
use app\common\model\waimai\AccountLog;

class MoneyService
{
    
    /**
     * @notes 用户余额变更
     * @param $uid  商户id
     * @param $change_type 变动类型;[1=充值,2=提现,3=订单收款,4=后台操作]
     * @param $action 动作 1-增加 2-减少
     * @param $change_amount 变动金额
     * @param $source_sn 关联ID
     * @param $remark 备注
     * @param create_by 创建者id
     */
    public static function adminMoney($uid, $change_type, $action,$change_amount, $source_sn="",  $remark='',$create_by="0"){
        $logPrefix="AdminMoney";
        if(empty($uid)||empty($change_type)||empty($action)||empty($change_amount)){
            addLog($logPrefix,1,"","",$uid);
            addLog($logPrefix,0,[$uid,$change_type,$action,$change_amount,$source_sn, $remark,$create_by],"参数不存在",$uid);
            addLog($logPrefix,2,"","",$uid);
            return ["code"=>0,"msg"=>"参数不存在"];
        }
        addLog($logPrefix,1,"","",$uid);
        addLog($logPrefix,0,[$uid,$change_type,$action,$change_amount,$source_sn, $remark,$create_by],"参数",$uid);
        $User = Admin::where(['id' => $uid])->lock(true)->find();
        addLog($logPrefix,0,$User,"用户信息",$uid);
        
        $left_amount=floatval($User->user_money);
        addLog($logPrefix,0,[$left_amount],"变更前余额",$uid);
        $change_amount=floatval($change_amount);
        addLog($logPrefix,0,[$change_amount],"变更余额",$uid);
        if($change_amount<=0){
            addLog($logPrefix,0,$change_amount,"变更余额小于或等于0",$uid);
            addLog($logPrefix,2,"","",$uid);
            return ["code"=>0,"msg"=>"变更余额小于或等于0"];
        }
        // 余额增加
        if($action==1){
            $right_amount=0;
            $upMode=Admin::where(['id' => $uid])->inc('user_money', $change_amount)->update();
            if($change_type==1){
                $upMode=Admin::where(['id' => $uid])->inc('total_recharge_amount', $change_amount)->update();
            }
            // $right_amount=$upMode->user_money;
            $sql1=Admin::getLastSql();
            $right_amount=Admin::where(['id' => $uid])->value('user_money');
            $sql2=Admin::getLastSql();
            addLog($logPrefix,0,[$sql1,$sql2,$right_amount],"变更后余额",$uid);
            $logModel=self::AccountLog($uid,$change_type,$action,$left_amount,$change_amount,$right_amount, $source_sn, $remark,$create_by);
            addLog($logPrefix,0,$logModel,"新增流水返回信息",$uid);
            addLog($logPrefix,2,"","",$uid);
            return ["code"=>1,"msg"=>"增加余额成功"];
        }
        
        // 余额减少
        if($action==2){
            if($left_amount<$change_amount){
                addLog($logPrefix,0,[$left_amount,$change_amount],"金额不足扣除",$uid);
                addLog($logPrefix,2,"","",$uid);
                return ["code"=>0,"msg"=>"金额不足扣除"];
            }
            
            $setuser=Admin::where(['id' => $uid])->dec('user_money', $change_amount)->update();
            $sql1=Admin::getLastSql();
            $right_amount=Admin::where(['id' => $uid])->value('user_money');
            $sql2=Admin::getLastSql();
            addLog($logPrefix,0,[$right_amount,$setuser,$sql1,$sql2],"变更后余额",$uid);
            $logModel=self::AccountLog($uid,$change_type,$action,$left_amount,$change_amount,$right_amount, $source_sn, $remark,$create_by);
            addLog($logPrefix,0,$logModel,"新增流水返回信息",$uid);
            addLog($logPrefix,2,"","",$uid);
            return ["code"=>1,"msg"=>"扣除余额成功"];
        }
        return ["code"=>0,"msg"=>"未匹配到规则"];
    }
    
    /**
     * @notes 日志重写功能
     * @param $prefix文件类型
     * @param string $start 0 正常记录,1开始,2结束
     * @param null $data
     * @param null  $tt 标题
     * @return array|int|mixed|string
     */
    public static function AccountLog($uid,$change_type,$action,$left_amount,$change_amount,$right_amount,$source_sn="", $remark='',$create_by="0")
    {
        $order_sn=generate_sn(AccountLog::class, 'order_sn',"LS");
        $ip=getClientIP();
        $model=AccountLog::create([
            'order_sn' => $order_sn,
            'uid' => $uid,
            'change_type' => $change_type,
            'action' => $action,
            'left_amount' => $left_amount,
            'change_amount' => $change_amount,
            'right_amount' => $right_amount,
            'source_sn' => $source_sn,
            'ip' => $ip,
            'remark' => $remark,
            'update_time' => time(),
            'create_by' => $create_by,
        ]);
        return $model;
    }
    
}
