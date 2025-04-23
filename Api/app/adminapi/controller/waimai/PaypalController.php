<?php


namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\PaypalLists;
use app\adminapi\validate\waimai\PaypalValidate;
use app\common\model\waimai\{Paypal,PaypalUser};
use app\common\service\FileService;
use app\adminapi\lists\waimai\UserLists;

/**
 * 支付通道管理控制器
 * Class PaypalController
 * @package app\adminapi\controller\article
 */
class PaypalController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new PaypalLists());
    }


    /**
     * @notes  添加资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:31
     */
    public function add()
    {
        $params = (new PaypalValidate())->post()->goCheck('add');
        Paypal::create([
            'name' => $params['name'],
            'fee_type' => $params['fee_type'],
            'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
            'client_id' => $params['client_id'],
            'secret_key' => $params['secret_key'],
            'sort' => $params['sort'] ?? 0,
            'status' => $params['status'],
            "create_by"=>$this->adminId,
            "update_by"=>$this->adminId
        ]);
        return $this->success('添加成功', [], 1, 1);
    }

      /**
         * @notes  查看管理员列表
         * @return \think\response\Json
         * @author heshihu
         * @date 2022/2/22 9:47
         */
        public function adminlists()
        {
            // return $this->success('添加成功', [$this->adminInfo], 1, 1);
            return $this->dataLists(new UserLists());
        }

    /**
     * @notes  编辑资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:49
     */
    public function edit()
    {
        $params = (new PaypalValidate())->post()->goCheck('edit');
        try {
            Paypal::update([
                'id' => $params['id'],
                'name' => $params['name'],
                'fee_type' => $params['fee_type'],
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'client_id' => $params['client_id'],
                'secret_key' => $params['secret_key'],
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
        $params = (new PaypalValidate())->post()->goCheck('delete');
        Paypal::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        Paypal::destroy($params['id']);
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
        $params = (new PaypalValidate())->goCheck('detail');
        $result = Paypal::findOrEmpty($params['id'])->toArray();
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
        $params = (new PaypalValidate())->post()->goCheck('status');
        Paypal::update([
            'id' => $params['id'],
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ]);
        PaypalUser::update([
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ],['payid' => $params['id']]);
        return $this->success('修改成功', [], 1, 1);
    }


    /**
     * @notes 获取文章分类
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/10/13 10:54
     */
    public function all()
    {
        $result = Paypal::where(['status' => 1])
            ->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()
            ->toArray();
        return $this->data($result);
    }
    // 分配用户
    public function fenpei()
    {
        $rate=input('rate',"");
        $payid=input('payid',"");
        $uid=input('uid',"");
        if(empty($uid)){
            return $this->fail('缺失分配用户信息');
        }
        if(empty($payid)){
                return $this->fail('缺失支付信息');
            }
            $Model=PaypalUser::where(['payid' => $payid,'uid'=>$uid])->findOrEmpty();
            if(!$Model->isEmpty()){
                 return $this->fail('已经分配此支付了');
            }
            $result = Paypal::findOrEmpty($payid)->toArray();
            try {
                PaypalUser::create([
                    'payid' => $payid,
                    'uid' => $uid,
                    'name' => $result['name'] ?? "",
                    'image' => $result['image'] ?? "",
                    'fee_type' => $result['fee_type'] ?? "USD",
                    'rate' => $rate ?? 0,
                    'sort' => $result['sort'] ?? "",
                    'status' => 1,
                    "create_by"=>$this->adminId,
                    "update_by"=>$this->adminId,
                ]);
                return $this->success('分配成功', [], 1, 1);
            } catch (\Exception $e) {
               return $this->fail($e->getMessage());
            }
    }

}