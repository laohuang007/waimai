<?php


namespace app\adminapi\lists\waimai;

use app\adminapi\lists\BaseAdminDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\lists\ListsSortInterface;
use app\common\lists\ListsExcelInterface;
use app\common\model\waimai\Recharge;

/**
 * 分类列表
 * Class ArticleCateLists
 * @package app\adminapi\lists\article
 */
class RechargeLists extends BaseAdminDataLists implements ListsSearchInterface, ListsSortInterface,ListsExcelInterface
{


 /**
     * @notes 导出字段
     * @return string[]
     */
    public function setExcelFields(): array
    {
        return [
            'order_sn' => '订单号',
            'user_name' => '用户昵称',
            'pay_type_text' => '支付类型',
            'status_text' => '审核状态',
            'pay_time' => '审核时间',
            'order_amount' => '充值金额',
            'rate' => '汇率',
            'service_charge' => '手续费',
            'reality_amount' => '到账金额',
            'ip' => 'ip',
            'remark' => '备注',
            'update_name' => '修改者昵称',
            'update_time' => '修改时间',
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
        return '充值记录';
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
                '=' => [ 'order_sn','uid','pay_type','status','update_by','create_by']
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
        $lists = Recharge::where($this->searchWhere)
            ->where($this->queryWhere())
            ->append(['user_name','create_name','update_name'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order($this->sortOrder)
            ->select()
            ->toArray();
        $statusList=[0,"待审核",1=>"已审核",2=>"审核失败"];
        $pay_typeList=[1=>"USD",2=>"银行卡"];
        foreach ($lists as &$item) {
            $item['pay_time']=date("Y-m-d H:i:s",$item['pay_time']);
            $item['pay_type_text']=$pay_typeList[$item['pay_type']]??'位置类型';
            $item['status_text']=$statusList[$item['status']]??'未知状态';
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
        return Recharge::where($this->searchWhere)->where($this->queryWhere())->count();
    }

    public function extend()
    {
        return [];
    }
}