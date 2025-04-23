<?php


namespace app\adminapi\lists\waimai;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\lists\ListsSortInterface;
use app\common\lists\ListsExcelInterface;
use app\common\model\waimai\AccountLog;

/**
 * 分类列表
 * Class ArticleCateLists
 * @package app\adminapi\lists\article
 */
class AccountLogLists extends BaseAdminDataLists implements ListsSearchInterface, ListsSortInterface,ListsExcelInterface
{


 /**
     * @notes 导出字段
     * @return string[]
     */
    public function setExcelFields(): array
    {
        return [
            'order_sn' => '流水号',
            'user_name' => '用户昵称',
            'change_type_text' => '变动类型',
            'action_text' => '变动动作',
            'left_amount' => '变动前数量',
            'change_amount' => '变动数量',
            'right_amount' => '变动后数量',
            'source_sn' => '关联单号',
            'ip' => 'IP',
            'remark' => '备注',
            'create_name' => '创建者昵称',
            'create_time' => '创建时间',
        ];
    }


    /**
     * @notes 导出表名
     * @return string
     */
    public function setFileName(): string
    {
        return '流水记录';
    }

    /**
     * @notes  设置搜索条件
     * @return array
     * @author heshihu
     * @date 2022/2/8 18:39
     */
    public function setSearch(): array
    {
         return [
            '%like%' => ['ip','remark'],
                '=' => [ 'order_sn','uid','change_type','action','source_sn','create_by']
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
        $lists = AccountLog::where($this->searchWhere)
            ->where($this->queryWhere())
            ->append(['user_name','create_name'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order($this->sortOrder)
            ->select()
            ->toArray();
        $changeList=[1=>"充值",2=>"提现",3=>"订单收款",4=>"系统操作"];
        $actionList=[1=>"增加",2=>"减少"];
        foreach ($lists as &$item) {
            
            $item['change_type_text']=$changeList[$item['change_type']]??'未知类型';
            $item['action_text']=$actionList[$item['action']]??'未知动作';
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
        return AccountLog::where($this->searchWhere)->where($this->queryWhere())->count();
    }

    public function extend()
    {
        return [];
    }
}