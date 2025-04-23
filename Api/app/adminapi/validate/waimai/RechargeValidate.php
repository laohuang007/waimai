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
use app\common\model\waimai\Recharge;

/**
 * 商品管理验证
 * Class  RechargeValidate
 */
class RechargeValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkRecharge',
        'status' => 'require|in:0,1,2',
        'order_amount' => 'require|number',
    ];

    protected $message = [
        'id.require' => '订单id不能为空',
        'status.require' => '审核状态必须存在',
        'status.in' => '审核状态有误',
        'order_amount.require' => '充值金额不能为空',
        'order_amount.number' => '充值金额必须是整数',
    ];

    /**
     * @notes  关闭
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 9:57
     */
    public function sceneAdd()
    {
        return $this->only(['order_amount']);
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
     * @notes  详情场景
     * @return GoodsValidate
     * @author heshihu
     * @date 2022/2/22 10:15
     */
    public function sceneUpdateStatus()
    {
        return $this->only(['id','status']);
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
    public function checkRecharge($value)
    {
        $article = Recharge::findOrEmpty($value);
        if ($article->isEmpty()) {
            return '订单不存在';
        }
        return true;
    }

}