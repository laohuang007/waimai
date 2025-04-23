<?php


namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\OrdersLists;
use app\adminapi\validate\waimai\OrdersValidate;
use app\common\model\waimai\{Orders};
use app\common\service\FileService;
use app\common\service\ConfigService;
/**
 * 订单管理控制器
 * Class OrdersController
 * @package app\adminapi\controller\article
 */
class OrdersController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new OrdersLists());
    }
    
    // 关闭订单
    public function close()
    {
        $params = (new OrdersValidate())->post()->goCheck('close');
        // return $this->fail("测试");
        $Model=Orders::where(['id' =>$params['id']])->findOrEmpty();
        if($Model->isEmpty()){
             return $this->fail('订单不存在?');
        }
        if($Model->status>=1){
            return $this->fail('订单状态已经改变了,不可以进行关闭了');
        }
        try {
            Orders::update([
                'id' => $params['id'],
                'status_time' => time(),
                'status' => 4,//系统关闭
                "update_by"=>$this->adminId
            ]);
            return $this->success('关闭成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }
    // 修改订单价格
    public function updatePrice()
    {
        $params = (new OrdersValidate())->post()->goCheck('updatePrice');
        $Model=Orders::where(['id' =>$params['id']])->findOrEmpty();
        if($Model->isEmpty()){
             return $this->fail('订单不存在');
        }
        if($Model->status>=1){
            return $this->fail('订单状态已经改变了,不可以进行改价了');
        }
        if($Model->order_amount==$params['order_amount']){
            return $this->fail('价格一样,不需要修改');
        }
        try {
            Orders::update([
                'id' => $params['id'],
                'order_amount' => floatval($params['order_amount']),
                'pay_url'=>"",
                "update_by"=>$this->adminId
            ]);
            return $this->success('修改成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }
    // 查看订单链接
    public function looklink()
    {
        $params = (new OrdersValidate())->post()->goCheck('looklink');
        $result = Orders::findOrEmpty($params['id'])->toArray();
        $payurl=ConfigService::get('website', 'shop_url', "https://pay.pay.ttcni.top");
        $res=["url"=>$payurl.'/#/pay/'.$result['random']];
        return $this->data($res);
    }
    // 删除订单
     public function delete()
    {
        $params = (new OrdersValidate())->post()->goCheck('delete');
        Orders::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        Orders::destroy($params['id']);
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
        $params = (new OrdersValidate())->goCheck('detail');
        $result = Orders::findOrEmpty($params['id'])->toArray();
        return $this->data($result);
    }




}