<?php


namespace app\adminapi\lists\waimai;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\lists\ListsSortInterface;
use app\common\model\waimai\Orders;

/**
 * 分类列表
 * Class ArticleCateLists
 * @package app\adminapi\lists\article
 */
class OrdersLists extends BaseAdminDataLists implements ListsSearchInterface, ListsSortInterface
{


    /**
     * @notes  设置搜索条件
     * @return array
     * @author heshihu
     * @date 2022/2/8 18:39
     */
    public function setSearch(): array
    {
         return [
            '%like%' => ['name','payer_name','payer_email'],
            '=' => [ 'status','payer_id','order_sn','gid','uid','payid']
        ];
    }

    /**
     * @notes  设置支持排序字段
     * @return array
     * @author heshihu
     * @date 2022/2/9 15:11
     */
    public function setSortFields(): array
    {
        return ['create_time' => 'create_time', 'id' => 'id'];
    }

    /**
     * @notes  设置默认排序
     * @return array
     * @author heshihu
     * @date 2022/2/9 15:08
     */
    public function setDefaultOrder(): array
    {
        return ['create_time' => 'desc','id' => 'desc'];
    }

    public function queryWhere()
    {
        $where = [];
        if($this->adminInfo['is_user']==1){
            $where[] =  ['uid', '=', $this->adminInfo['id']];
        }
        return $where;
    }
    /**
     * @notes  获取管理列表
     * @return array
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists(): array
    {
        $lists = Orders::where($this->searchWhere)
            ->where($this->queryWhere())
            ->append(['user_name','create_name','update_name'])
            ->with(['paypal','goods'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order($this->sortOrder)
            ->select()
            ->toArray();
        foreach ($lists as &$item){
            $item['status_time']=date("Y-m-d H:s:i",$item['status_time']);
            $item['pay_time']=date("Y-m-d H:s:i",$item['pay_time']);
        }

        return $lists;
    }

    /**
     * @notes  获取数量
     * @return int
     * @author heshihu
     * @date 2022/2/9 15:12
     */
    public function count(): int
    {
        return Orders::where($this->searchWhere)->where($this->queryWhere())->count();
    }

    public function extend()
    {
        return [];
    }
}