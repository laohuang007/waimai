<?php
namespace app\common\model\waimai;

use app\common\model\BaseModel;
use app\common\model\auth\Admin;
use think\model\concern\SoftDelete;

class Orders extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

   // 设置json类型字段
	protected $json = ['paypal_data','payer_data'];
	// 设置JSON数据返回数组
    protected $jsonAssoc = true;

    public function getPaypalUserAttr($value, $data)
    {
        return PaypalUser::where('payid', $data['payid'])->where('uid', $data['uid'])->select();
    }
    public function getUserNameAttr($value, $data)
    {
        return Admin::where('id', $data['uid'])->value('name');
    }
    public function getCreateNameAttr($value, $data)
    {
        return Admin::where('id', $data['create_by'])->value('name');
    }
     public function getUpdateNameAttr($value, $data)
    {
        return Admin::where('id', $data['update_by'])->value('name');
    }
    public function goods()
    {
        return $this->hasOne(Goods::class, 'id','gid');
    }
    public function paypal()
    {
        return $this->hasOne(Paypal::class, 'id','payid');
    }
   

}