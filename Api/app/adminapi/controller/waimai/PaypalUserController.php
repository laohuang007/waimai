<?php


namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\PaypalUserLists;
use app\adminapi\validate\waimai\PaypalUserValidate;
use app\common\model\waimai\{Paypal,PaypalUser};
use app\common\service\FileService;
use app\adminapi\lists\waimai\UserLists;

/**
 * 支付通道管理控制器
 * Class PaypalController
 * @package app\adminapi\controller\article
 */
class PaypalUserController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new PaypalUserLists());
    }

    /**
     * @notes  编辑资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:49
     */
    public function edit()
    {
        $params = (new PaypalUserValidate())->post()->goCheck('edit');
        try {
            PaypalUser::update([
                'id' => $params['id'],
                'name' => $params['name'],
                'fee_type' => $params['fee_type'],
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'rate' => $params['rate'] ?? 0,
                'sort' => $params['sort'] ?? 0,
                'status' => $params['status'],
                "update_by"=>$this->adminId
            ]);
            return $this->success('编辑成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }


    /**
     * @notes  删除资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:52
     */
    public function delete()
    {
        $params = (new PaypalUserValidate())->post()->goCheck('delete');
        PaypalUser::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        PaypalUser::destroy($params['id']);
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
        $params = (new PaypalUserValidate())->goCheck('detail');
        $result = PaypalUser::findOrEmpty($params['id'])->toArray();
        return $this->data($result);
    }


    /**
     * @notes  更改资讯分类状态
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 10:15
     */
    public function updateStatus()
    {
        $params = (new PaypalUserValidate())->post()->goCheck('status');
        PaypalUser::update([
            'id' => $params['id'],
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ]);
        return $this->success('修改成功', [], 1, 1);
    }


}