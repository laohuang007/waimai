<?php


namespace app\common\model\waimai;

use app\common\model\BaseModel;
use app\common\model\auth\Admin;
use think\model\concern\SoftDelete;

class UserGoods extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

   


    public function goods()
    {
        return $this->hasOne(Goods::class, 'id','gid');
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
   

}