<?php


namespace app\adminapi\controller\waimai;

use app\adminapi\controller\BaseAdminController;
use app\adminapi\lists\waimai\AccountLogLists;
use app\adminapi\validate\waimai\AccountLogValidate;
use app\common\model\waimai\{AccountLog};
use app\common\service\FileService;
use app\common\service\ConfigService;
/**
 * 订单管理控制器
 * Class AccountLogController
 * @package app\adminapi\controller\article
 */
class AccountLogController extends BaseAdminController
{

    /**
     * @notes  查看资讯分类列表
     * @return \think\response\Json
     * @author heshihu
     * @date 2022/2/21 17:11
     */
    public function lists()
    {
        return $this->dataLists(new AccountLogLists());
    }
    




}