<?php

namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\GoodsLists;
use app\adminapi\lists\waimai\UserLists;
use app\adminapi\validate\waimai\GoodsValidate;
use app\common\service\FileService;
use app\common\model\waimai\{Goods,UserGoods};

/**
 * 商品管理控制器
 */
class GoodsController extends BaseAdminController
{

    /**
     * @notes  查看资讯列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 9:47
     */
    public function lists()
    {
        return $this->dataLists(new GoodsLists());
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
     * @notes  添加资讯
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 9:57
     */
    public function add()
    {
        $params = (new GoodsValidate())->post()->goCheck('add');
        // return $this->success('添加成功', [], 1, 1);
        try {
            Goods::create([
                'cid' => $params['cid'],
                'name' => $params['name'],
                'image' => $params['image'] ? FileService::setFileUrl($params['image']) : '',
                'amount' => $params['amount'] ?? 0,
                'reality_amount' => $params['reality_amount'] ?? 0,
                'fee_type' => $params['fee_type'] ?? 'USD',
                'content' => $params['content'] ?? '',
                'sort' => $params['sort'] ?? 0, // 排序
                'status' => $params['status'],
                "create_by"=>$this->adminId,
                "update_by"=>$this->adminId,
            ]);
            return $this->success('添加成功', [], 1, 1);
        } catch (\Exception $e) {
           return $this->fail($e->getMessage());
        }
    }

    /**
     * @notes  编辑资讯
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/22 10:12
     */
    public function edit()
    {
        $params = (new GoodsValidate())->post()->goCheck('edit');
        try {
            $result=Goods::update([
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
        $params = (new GoodsValidate())->post()->goCheck('delete');
        $Model=UserGoods::where(['gid' => $params['id']])->findOrEmpty();
        if(!$Model->isEmpty()){
             return $this->fail('已经分配此商品了，请先删除分配的商品');
        }
        Goods::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        Goods::destroy($params['id']);
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
        $params = (new GoodsValidate())->goCheck('detail');
        $result = Goods::findOrEmpty($params['id'])->toArray();
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
        $params = (new GoodsValidate())->post()->goCheck('status');
        Goods::update([
            'id' => $params['id'],
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ]);
        return $this->success('修改成功', [], 1, 1);
    }
    
    // 分配用户
    public function fenpei()
    {
        $type=input('type',"");
        $gid=input('gid',"");
        $uid=input('uid',"");
        $gidList=input('gidList',"");
        if(empty($uid)){
            return $this->fail('缺失分配用户信息');
        }
        if($type=="one"){
            if(empty($gid)){
                return $this->fail('缺失商品信息');
            }
            $Model=UserGoods::where(['gid' => $gid,'uid'=>$uid])->findOrEmpty();
            if(!$Model->isEmpty()){
                 return $this->fail('已经分配此商品了');
            }
            $result = Goods::findOrEmpty($gid)->toArray();
            try {
                UserGoods::create([
                    'gid' => $gid,
                    'uid' => $uid,
                    'name' => $result['name'] ?? "",
                    'image' => $result['image'] ?? "",
                    'amount' => $result['amount'] ?? 0,
                    'reality_amount' => $result['reality_amount'] ?? 0,
                    'fee_type' => $result['fee_type'] ?? "USD",
                    'content' => $result['content'] ?? "",
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
        if($type=="all"){
            if(empty($gidList)){
                return $this->fail('缺失商品信息');
            }
            $err=[];$cg=[];
            foreach ($gidList as $id){
                $Model=UserGoods::where(['gid' => $id,'uid'=>$uid])->findOrEmpty();
                if($Model->isEmpty()){
                    $result = Goods::findOrEmpty($id)->toArray();
                    try {
                        UserGoods::create([
                            'gid' => $id,
                            'uid' => $uid,
                            'name' => $result['name'] ?? "",
                            'image' => $result['image'] ?? "",
                            'amount' => $result['amount'] ?? 0,
                            'reality_amount' => $result['reality_amount'] ?? 0,
                            'fee_type' => $result['fee_type'] ?? "USD",
                            'content' => $result['content'] ?? "",
                            'sort' => $result['sort'] ?? "",
                            'status' => 1,
                            "create_by"=>$this->adminId,
                            "update_by"=>$this->adminId,
                        ]);
                        $cg[]=$id;
                    } catch (\Exception $e) {
                        $err[]=$e->getMessage();
                    }
                }else{
                    $err[]=$id;
                }
                $msg="分配成功".count($cg)."条;分配失败".count($err)."条";
                return $this->success($msg, [$err,$cg], 1, 1);
            }
        }
        return $this->fail('系统错误');
    }


}