<?php


namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\GoodsCateLists;
use app\adminapi\validate\waimai\GoodsCateValidate;
use app\common\model\waimai\GoodsCate;

/**
 * 商品分类管理控制器
 * Class GoodsCateController
 * @package app\adminapi\controller\article
 */
class GoodsCateController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new GoodsCateLists());
    }


    /**
     * @notes  添加资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:31
     */
    public function add()
    {
        $params = (new GoodsCateValidate())->post()->goCheck('add');
        GoodsCate::create([
            'name' => $params['name'],
            'status' => $params['status'],
            'sort' => $params['sort'] ?? 0,
            "create_by"=>$this->adminId,
            "update_by"=>$this->adminId
        ]);
        return $this->success('添加成功', [], 1, 1);
    }


    /**
     * @notes  编辑资讯分类
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:49
     */
    public function edit()
    {
        $params = (new GoodsCateValidate())->post()->goCheck('edit');
        try {
            GoodsCate::update([
                'id' => $params['id'],
                'name' => $params['name'],
                'status' => $params['status'],
                'sort' => $params['sort'] ?? 0,
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
        $params = (new GoodsCateValidate())->post()->goCheck('delete');
        GoodsCate::update([
            'id' => $params['id'],
            "update_by"=>$this->adminId,
        ]);
        GoodsCate::destroy($params['id']);
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
        $params = (new GoodsCateValidate())->goCheck('detail');
        $result = GoodsCate::findOrEmpty($params['id'])->toArray();
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
        $params = (new GoodsCateValidate())->post()->goCheck('status');
        GoodsCate::update([
            'id' => $params['id'],
            'status' => $params['status'],
            "update_by"=>$this->adminId,
        ]);
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
        $result = GoodsCate::where(['status' => 1])
            ->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()
            ->toArray();
        return $this->data($result);
    }


}