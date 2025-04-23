<?php

namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\UserGoodsLists;
use app\adminapi\lists\waimai\UserLists;
use app\adminapi\validate\waimai\UserGoodsValidate;
use app\common\service\FileService;
use app\common\model\waimai\{Goods,UserGoods,PaypalUser,Orders};
use app\common\service\pay\PaypalService;
use app\common\service\ConfigService;

/**
 * 商品管理控制器
 */
class UserGoodsController extends BaseAdminController
{

    /**
     * @notes  查看资讯列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 9:47
     */
    public function lists()
    {
        return $this->dataLists(new UserGoodsLists());
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

    // 创建订单
    public function addOrder()
    {
        $params = (new UserGoodsValidate())->post()->goCheck('addOrder');
        // return $this->success('编辑成功', [$params], 1, 1);
        $goodsuser = UserGoods::findOrEmpty($params['id'])->toArray();
        $paypaluser=PaypalUser::where(["id"=>$params['payid']])->with(['paypal'])->findOrEmpty();
        if($paypaluser->isEmpty()){
            return $this->fail("支付通道不存在");
        }
        if($paypaluser['status']!=1){
            return $this->fail("支付通道已关闭");
        }
        if(empty($paypaluser['paypal'])){
            return $this->fail("支付通道不存在");
        }
        if($paypaluser['paypal']['status']!=1){
            return $this->fail("支付通道已关闭");
        }
        $uid=$goodsuser['uid'];
        $order_sn=generate_sn(Orders::class, 'order_sn','paypal');
        $order_name=$params['name'];
        $fee_type=$params['fee_type'];
        $fee=floatval($params['order_amount']);
        $rate=floatval($paypaluser['rate'])*$fee;
        $reality_amount=$fee-$rate;
        $random=random(Orders::class,'random',4);
        $data=[
            "order_sn"=>$order_sn,
            "gid"=>$params['id'],
            "uid"=>$uid,
            "payid"=>$paypaluser['payid'],
            "name"=>$params['name'],
            "image"=>$params['image'] ? FileService::setFileUrl($params['image']) : '',
            "amount"=>$params['amount'],
            "order_amount"=>$fee,
            "fee_type"=>$fee_type,
            "content"=>$params['content'],
            "rate"=>$rate,
            "random"=>$random,
            "reality_amount"=>$reality_amount,
            "create_by"=>$this->adminId,
            "update_by"=>$this->adminId
            
            
        ];
        try {
            Orders::create($data);
            $payurl=ConfigService::get('website', 'shop_url', "https://pay.pay.ttcni.top");
            $res=["url"=>$payurl.'/#/pay/'.$random];
            return $this->success('订单创建成功', $res, 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
        // return $this->success('编辑成功', [$params,$paypaluser,$res,[$order_sn,$order_name,$fee_type,$fee]], 1, 1);
        
        // $pay=new PaypalService($paypaluser['paypal']['client_id'],$paypaluser['paypal']['secret_key']);
        // $res=$pay->create($order_sn,$order_name,$fee_type,$fee);
        // $res=$pay->getToken();
        // return $this->success('编辑成功', [$data], 1, 1);
         return $this->fail('测试',$data);
        
    }

    // 获取支付通道
    public function paypal()
    {
        $id=input("id","");
        if(!empty($id)){
            $goodsuser = UserGoods::findOrEmpty($id)->toArray();
            $uid=$goodsuser['uid'];
        }else{
            $uid=$this->adminId;
        }
        
        $result = PaypalUser::where(['status' => 1,'uid'=>$uid])
            ->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()
            ->toArray();
        return $this->data($result);
    }

    /**
     * @notes  编辑资讯
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 10:12
     */
    public function edit()
    {
        $params = (new UserGoodsValidate())->post()->goCheck('edit');
        try {
            $result=UserGoods::update([
                'id' => $params['id'],
                'name' => $params['name'],
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'amount' => $params['amount'] ?? 0,
                'reality_amount' => $params['reality_amount'] ?? 0,
                'fee_type' => $params['fee_type'] ?? 'USD',
                'content' => $params['content'] ?? '',
                'sort' => $params['sort'] ?? 0, // 排序
                'status' => $params['status'],
                "update_by"=>$this->adminId,
            ]);
            return $this->success('编辑成功', [], 1, 1);
         } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes  删除资讯
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 10:17
     */
    public function delete()
    {
        $params = (new UserGoodsValidate())->post()->goCheck('delete');
        UserGoods::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        UserGoods::destroy($params['id']);
        return $this->success('删除成功', [], 1, 1);
    }

    /**
     * @notes  资讯详情
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 10:15
     */
    public function detail()
    {
        $params = (new UserGoodsValidate())->goCheck('detail');
        $result = UserGoods::findOrEmpty($params['id'])->toArray();
        return $this->data($result);
    }


    /**
     * @notes  更改资讯状态
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 10:18
     */
    public function updateStatus()
    {
        $params = (new UserGoodsValidate())->post()->goCheck('status');
        UserGoods::update([
            'id' => $params['id'],
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ]);
        return $this->success('修改成功', [], 1, 1);
    }
    
    // 重置商品
    public function huifu()
    {
        $params = (new UserGoodsValidate())->goCheck('huifu');
         try {
            $result = UserGoods::where(["id"=>$params['id']])->with(['goods'])->findOrEmpty();
            // return $this->success('充值成功', [$result], 1, 1);
            $res=UserGoods::update([
                'id' => $params['id'],
                'name' => $result['goods']['name'],
                'image' => $result['goods']['image'],
                'amount' => $result['goods']['amount'] ?? 0,
                'reality_amount' => $result['goods']['reality_amount'] ?? 0,
                'fee_type' => $result['goods']['fee_type'] ?? 'USD',
                'content' => $result['goods']['content'] ?? '',
                "update_by"=>$this->adminId,
            ]);
            return $this->success('重置成功', [], 1, 1);
         } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        
    }


}