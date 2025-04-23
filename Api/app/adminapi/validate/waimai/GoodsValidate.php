<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\validate\waimai;

use app\common\validate\BaseValidate;
use app\common\model\waimai\Goods;

/**
 * 商品管理验证
 * Class GoodsValidate
 */
class GoodsValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkGoods',
        'name' => 'require|length:1,255',
        'image' => 'require',
        'cid' => 'require',
        'amount' => 'require',
        'status' => 'require|in:0,1',
        'reality_amount' => 'require',
        'fee_type' => 'require',
    ];

    protected $message = [
        'id.require' => '商品id不能为空',
        'name.require' => '标题不能为空',
        'name.length' => '标题长度须在1-255位字符',
        'image.require' => '封面图必须存在',
        'amount.require' => '商品价格必须存在',
        'reality_amount.require' => '商品实际价格必须存在',
        'fee_type.require' => '商品价格单位必须存在',
    ];

    /**
     * @notes  添加场景
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 9:57
     */
    public function sceneAdd()
    {
        return $this->remove(['id'])
            ->remove('id','require|checkGoods');
    }

    /**
     * @notes  详情场景
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 10:15
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes  更改状态场景
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 10:18
     */
    public function sceneStatus()
    {
        return $this->only(['id', 'status']);
    }

    public function sceneEdit()
    {
    }

    /**
     * @notes  删除场景
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 10:17
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }

    /**
     * @notes  检查指定资讯是否存在
     * @param $value
     * @return bool|string
     * @author heshihu
     * @date 2022/2/22 10:11
     */
    public function checkGoods($value)
    {
        $article = Goods::findOrEmpty($value);
        if ($article->isEmpty()) {
            return '商品不存在';
        }
        return true;
    }

}