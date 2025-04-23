import request from '@/utils/request'

// 支付列表
export function paypalLists(params?: any) {
    return request.get({ url: '/waimai.paypal/lists', params })
}
// 支付列表
export function paypalAll(params?: any) {
    return request.get({ url: '/waimai.paypal/all', params })
}

// 添加支付
export function paypalAdd(params: any) {
    return request.post({ url: '/waimai.paypal/add', params })
}

// 编辑支付
export function paypalEdit(params: any) {
    return request.post({ url: '/waimai.paypal/edit', params })
}

// 删除支付
export function paypalDelete(params: any) {
    return request.post({ url: '/waimai.paypal/delete', params })
}

// 支付详情
export function paypalDetail(params: any) {
    return request.get({ url: '/waimai.paypal/detail', params })
}

// 支付状态
export function paypalStatus(params: any) {
    return request.post({ url: '/waimai.paypal/updateStatus', params })
}

// 分配支付
export function paypalFenpei(params: any) {
    return request.post({ url: '/waimai.paypal/fenpei', params })
}
// 用户列表
export function adminLists(params?: any) {
    return request.get({ url: '/waimai.paypal/adminlists', params })
}





// 商品列表
export function paypalUserLists(params?: any) {
    return request.get({ url: '/waimai.paypalUser/lists', params })
}


// 编辑商品
export function paypalUserEdit(params: any) {
    return request.post({ url: '/waimai.paypalUser/edit', params })
}

// 删除商品
export function paypalUserDelete(params: any) {
    return request.post({ url: '/waimai.paypalUser/delete', params })
}

// 商品详情
export function paypalUserDetail(params: any) {
    return request.get({ url: '/waimai.paypalUser/detail', params })
}

// 支付状态
export function paypalUserStatus(params: any) {
    return request.post({ url: '/waimai.paypalUser/updateStatus', params })
}
