<?php
namespace app\common\model\waimai;

use app\common\model\BaseModel;
use app\common\model\auth\Admin;
use think\model\concern\SoftDelete;

class GoodsCate extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

     public function getCreateNameAttr($value, $data)
    {
        return Admin::where('id', $data['create_by'])->value('name');
    }
    public function getUpdateNameAttr($value, $data)
    {
        return Admin::where('id', $data['update_by'])->value('name');
    }
       /**
     * @notes 文章数量
     * @param $value
     * @param $data
     * @return int
     * @author 段誉
     * @date 2022/9/15 11:32
     */
    public function getGoodsCountAttr($value, $data)
    {
        return Goods::where(['cid' => $data['id']])->count('id');
    }
   

}