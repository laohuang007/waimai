<?php


namespace app\common\model\waimai;

use app\common\model\auth\Admin;
use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class AccountLog extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

   


    public function getUserNameAttr($value, $data)
    {
        return Admin::where('id', $data['uid'])->value('name');
    }
     public function getCreateNameAttr($value, $data)
    {
        return Admin::where('id', $data['create_by'])->value('name');
    }

}