<?php

namespace app\adminapi\validate\waimai;

use app\common\validate\BaseValidate;
use app\common\model\waimai\{Paypal,PaypalUser};

/**
 * 分类管理验证
 * Class ArticleCateValidate
 * @package app\adminapi\validate\article
 */
class PaypalUserValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkPaypalUser',
        'name' => 'require|length:1,90',
        'image' => 'require',
        'rate' => 'require',
        'status' => 'require|in:0,1',
        'sort' => 'egt:0',
    ];

    protected $message = [
        'id.require' => 'id不能为空',
        'name.require' => '支付名称不能为空',
        'image.require' => '支付图片不能为空',
        'rate.require' => '费率不能为空',
        'name.length' => '分类长度须在1-90位字符',
        'sort.egt' => '排序值不正确',
    ];

    /**
     * @notes  添加场景
     * @return ArticleCateValidate
     * @author heshihu
     * @date 2022/2/10 15:11
     */
    public function sceneAdd()
    {
        return $this->remove(['id'])
            ->remove('id', 'require|checkPaypalUser');
    }

    /**
     * @notes  详情场景
     * @return ArticleCateValidate
     * @author heshihu
     * @date 2022/2/21 17:55
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes  更改状态场景
     * @return ArticleCateValidate
     * @author heshihu
     * @date 2022/2/21 18:02
     */
    public function sceneStatus()
    {
        return $this->only(['id', 'status']);
    }

    public function sceneEdit()
    {
    }

    /**
     * @notes  获取所有分类场景
     * @return ArticleCateValidate
     * @author heshihu
     * @date 2022/2/15 10:05
     */
    public function sceneSelect()
    {
        return $this->only(['type']);
    }


    /**
     * @notes  删除场景
     * @return ArticleCateValidate
     * @author heshihu
     * @date 2022/2/21 17:52
     */
    public function sceneDelete()
    {
        return $this->only(['id'])
            ->append('id');
    }

    /**
     * @notes  检查指定分类是否存在
     * @param $value
     * @return bool|string
     * @author heshihu
     * @date 2022/2/10 15:10
     */
    public function checkPaypalUser($value)
    {
        $article_category = PaypalUser::findOrEmpty($value);
        if ($article_category->isEmpty()) {
            return '支付不存在';
        }
        return true;
    }

}