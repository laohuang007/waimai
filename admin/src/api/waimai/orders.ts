import request from '@/utils/request'

// 数据分析
export function dataInfoLists(params?: any) {
    return request.get({ url: '/waimai.dataInfo/lists', params })
}
// 新增提现
export function dataInfoToday(params?: any) {
    return request.post({ url: '/waimai.dataInfo/today', params })
}
// 提现列表
export function withdrawLists(params?: any) {
    return request.get({ url: '/waimai.withdraw/lists', params })
}
// 新增提现
export function withdrawAdd(params?: any) {
    return request.post({ url: '/waimai.withdraw/add', params })
}
// 删除提现
export function withdrawDelete(params?: any) {
    return request.post({ url: '/waimai.withdraw/delete', params })
}
// 审核
export function withdrawUpdateStatus(params: any) {
    return request.post({ url: '/waimai.withdraw/updateStatus', params })
}
// 充值列表
export function rechargeLists(params?: any) {
    return request.get({ url: '/waimai.recharge/lists', params })
}
// 新增充值
export function rechargeAdd(params?: any) {
    return request.post({ url: '/waimai.recharge/add', params })
}
// 删除充值
export function rechargeDelete(params?: any) {
    return request.post({ url: '/waimai.recharge/delete', params })
}
export function rechargeUpdateStatus(params: any) {
    return request.post({ url: '/waimai.recharge/updateStatus', params })
}

// 流水列表
export function accountLogLists(params?: any) {
    return request.get({ url: '/waimai.accountLog/lists', params })
}
// 订单列表
export function ordersLists(params?: any) {
    return request.get({ url: '/waimai.orders/lists', params })
}
// 关闭订单
export function ordersClose(params?: any) {
    return request.post({ url: '/waimai.orders/close', params })
}

// 修改订单价格
export function ordersUpdatePrice(params: any) {
    return request.post({ url: '/waimai.orders/updatePrice', params })
}

// 查看订单链接
export function ordersLooklink(params: any) {
    return request.post({ url: '/waimai.orders/looklink', params })
}

// 订单详情
export function ordersDetail(params: any) {
    return request.get({ url: '/waimai.orders/detail', params })
}
// 删除订单
export function ordersDelete(params: any) {
    return request.post({ url: '/waimai.orders/delete', params })
}
