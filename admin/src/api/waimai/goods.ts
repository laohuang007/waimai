import request from '@/utils/request'

// 商品分类列表
export function goodsCateLists(params?: any) {
    return request.get({ url: '/waimai.goodsCate/lists', params })
}
// 商品分类列表
export function goodsCateAll(params?: any) {
    return request.get({ url: '/waimai.goodsCate/all', params })
}

// 添加商品分类
export function goodsCateAdd(params: any) {
    return request.post({ url: '/waimai.goodsCate/add', params })
}

// 编辑商品分类
export function goodsCateEdit(params: any) {
    return request.post({ url: '/waimai.goodsCate/edit', params })
}

// 删除商品分类
export function goodsCateDelete(params: any) {
    return request.post({ url: '/waimai.goodsCate/delete', params })
}

// 商品分类详情
export function goodsCateDetail(params: any) {
    return request.get({ url: '/waimai.goodsCate/detail', params })
}

// 商品分类状态
export function goodsCateStatus(params: any) {
    return request.post({ url: '/waimai.goodsCate/updateStatus', params })
}

// 商品列表
export function goodsLists(params?: any) {
    return request.get({ url: '/waimai.goods/lists', params })
}
// 商品列表
export function adminLists(params?: any) {
    return request.get({ url: '/waimai.goods/adminlists', params })
}
// 商品列表
export function goodsAll(params?: any) {
    return request.get({ url: '/goods/all', params })
}

// 添加商品
export function goodsAdd(params: any) {
    return request.post({ url: '/waimai.goods/add', params })
}
// 分配商品
export function goodsFenpei(params: any) {
    return request.post({ url: '/waimai.goods/fenpei', params })
}

// 编辑商品
export function goodsEdit(params: any) {
    return request.post({ url: '/waimai.goods/edit', params })
}

// 删除商品
export function goodsDelete(params: any) {
    return request.post({ url: '/waimai.goods/delete', params })
}

// 商品详情
export function goodsDetail(params: any) {
    return request.get({ url: '/waimai.goods/detail', params })
}

// 商品分类状态
export function goodsStatus(params: any) {
    return request.post({ url: '/waimai.goods/updateStatus', params })
}

// 用户商品列表
export function userGoodsLists(params?: any) {
    return request.get({ url: '/waimai.userGoods/lists', params })
}


// 编辑商品
export function userGoodsEdit(params: any) {
    return request.post({ url: '/waimai.userGoods/edit', params })
}

// 删除商品
export function userGoodsDelete(params: any) {
    return request.post({ url: '/waimai.userGoods/delete', params })
}

// 商品详情
export function userGoodsDetail(params: any) {
    return request.get({ url: '/waimai.userGoods/detail', params })
}

// 商品详情
export function userGoodsHuifu(params: any) {
    return request.get({ url: '/waimai.userGoods/huifu', params })
}

// 创建订单
export function userGoodsAddOrder(params: any) {
    return request.post({ url: '/waimai.userGoods/addOrder', params })
}

// 创建订单
export function userGoodsPaypal(params: any) {
    return request.post({ url: '/waimai.userGoods/Paypal', params })
}