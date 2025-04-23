<template>
    <div class="orders-lists">
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item class="w-[280px]" label="订单号">
                    <el-input
                        v-model="queryParams.order_sn"
                        placeholder="输入订单号"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="商品名称">
                    <el-input
                        v-model="queryParams.name"
                        placeholder="输入商品名称"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="支付邮箱">
                    <el-input
                        v-model="queryParams.payer_email"
                        placeholder="输入支付邮箱"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="支付姓名">
                    <el-input
                        v-model="queryParams.payer_name"
                        placeholder="输入支付姓名"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="支付通道ID">
                    <el-input
                        v-model="queryParams.payid"
                        placeholder="输入支付通道ID"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="用户ID">
                    <el-input
                        v-model="queryParams.uid"
                        placeholder="输入用户ID"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="商品原始ID">
                    <el-input
                        v-model="queryParams.gid"
                        placeholder="输入商品原始ID"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="状态">
                    <el-select v-model="queryParams.status">
                        <el-option label="全部" value />
                        <el-option label="待支付" :value="0" />
                        <el-option label="支付成功" :value="1" />
                        <el-option label="支付失败" :value="2" />
                        <el-option label="超时关闭" :value="3" />
                        <el-option label="手动关闭" :value="4" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large"  v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="订单号" prop="order_sn" min-width="220" />
                <el-table-column label="所属用户"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.user_name }}({{ row.uid }})
                    </template>
                </el-table-column>
                <el-table-column label="商品图片" min-width="100">
                    <template #default="{ row }">
                        <image-contain
                            v-if="row.image"
                            :src="row.image"
                            :width="60"
                            :height="45"
                            :preview-src-list="[row.image]"
                            preview-teleported
                            fit="contain"
                        />
                    </template>
                </el-table-column>
                <el-table-column
                    label="商品原始ID"
                    prop="gid"
                    min-width="100"
                    show-tooltip-when-overflow
                />
                <el-table-column
                    label="支付通道ID"
                    prop="payid"
                    min-width="100"
                    show-tooltip-when-overflow
                />
                <el-table-column
                    label="商品名称"
                    prop="name"
                    min-width="160"
                    show-tooltip-when-overflow
                />
                <el-table-column label="商品价格"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.amount }} {{ row.fee_type }}
                    </template>
                </el-table-column>
                <el-table-column label="订单价格"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.order_amount }} {{ row.fee_type }}
                    </template>
                </el-table-column>
                <el-table-column label="手续费"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.rate }} {{ row.fee_type }}
                    </template>
                </el-table-column>
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="state.statusText[row.status]?state.statusText[row.status].type:'primary'">{{state.statusText[row.status]?state.statusText[row.status].text:'未知'}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="支付时间" prop="pay_time" min-width="150" />
                <el-table-column label="支付人id" prop="payer_id" min-width="150" />
                <el-table-column label="支付人邮箱" prop="payer_email" min-width="150" />
                <el-table-column label="支付人姓名" prop="payer_name" min-width="150" />
                <el-table-column label="修改时间" prop="update_time" min-width="150" />
                <el-table-column label="修改人" prop="update_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="150" />
                <el-table-column label="创建人" prop="create_name" min-width="120" />
                <el-table-column label="操作" width="210" fixed="right" align="center">
                    <template #default="{ row }">
                        <el-button
                        v-perms="['waimai.orders/updatePrice']"
                        type="primary"
                        link
                        :disabled="row.status!=0"
                        @click="handleUp(row)"
                        >
                            改价
                        </el-button>
                        <el-button
                            v-perms="['waimai.orders/looklink']"
                            type="primary"
                            link
                            :disabled="row.status!=0"
                            @click="handleLikn(row)"
                            >
                            链接
                        </el-button>
                        <el-button
                            v-perms="['waimai.orders/close']"
                            type="danger"
                            link
                            :disabled="row.status!=0"
                            @click="handleClose(row.id)"
                        >
                            关闭
                        </el-button>
                        <el-button
                            v-perms="['waimai.orders/delete']"
                            type="danger"
                            link
                            :disabled="row.status!=0"
                            @click="handleDelete(row.id)"
                        >
                            删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <el-dialog v-model="showPrice" :show-close="false" width="500">
            <template #header="{ close, titleId, titleClass }">
            <div class="my-header">
                <h4 :id="titleId" :class="titleClass">修改订单价格
                    <el-button type="danger" class="float-right" @click="showPrice=false">
                        <el-icon class="el-icon--left"><CircleCloseFilled /></el-icon>
                        关闭
                    </el-button>
                </h4>
            </div>
            </template>
            <div>
                <el-input v-model="state.updatePrice.order_amount" placeholder="请输入订单价格" />
            </div>
            <div class="dialogbtn">
                <el-button type="primary" @click="priceSb">确定</el-button>
            </div>
        </el-dialog>
        <el-dialog v-model="showLink" :show-close="false" width="500">
            <template #header="{ close, titleId, titleClass }">
            <div class="my-header">
                <h4 :id="titleId" :class="titleClass">收银台地址
                    <el-button type="danger" class="float-right" @click="showLink=false">
                        <el-icon class="el-icon--left"><CircleCloseFilled /></el-icon>
                        关闭
                    </el-button>
                </h4>
            </div>
            </template>
            <div>
                <el-input
                    v-model="linkUrl"
                    style="max-width: 600px"
                    placeholder="url"
                    >
                    <template #append>
                        <el-button type="primary" class="float-right" v-copy="linkUrl">
                            <el-icon class="el-icon--left"><CopyDocument /></el-icon>
                            复制
                        </el-button>
                    </template>
                </el-input>
            </div>
        </el-dialog>
    </div>
</template>
<script lang="ts" setup name="ordersLists">
import {  ordersDelete,ordersLooklink, ordersLists, ordersClose, ordersUpdatePrice } from '@/api/waimai/orders'
import { useDictOptions } from '@/hooks/useDictOptions'
import { usePaging } from '@/hooks/usePaging'
import { getRoutePath } from '@/router'
import feedback from '@/utils/feedback'

const multipleSelection = ref([])

const handleSelectionChange = (val:[]) => {
  multipleSelection.value = val
}

const showLink = ref(false)
const linkUrl = ref("")
const showPrice = ref(false)

const state = reactive({
    statusText:[
        {"text":"待支付","type":"info"},
        {"text":"支付成功","type":"success"},
        {"text":"支付失败","type":"danger"},
        {"text":"超时关闭","type":"warning"},
        {"text":"手动关闭","type":"warning"},
    ],
    updatePrice:{
        id:"",
        order_amount:"",
    }
})
const queryParams = reactive({
    name: '',
    payid: '',
    status: '',
    uid: '',
    gid: '',
    order_sn: '',
    payer_id: '',
    payer_email: '',
    payer_name: ''
})
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: ordersLists,
    params: queryParams
})

const handleUp = async (row :any)=>{
    state.updatePrice={
        id:row.id,
        order_amount:row.order_amount
    };
    showPrice.value=true;
}
const priceSb = async ()=>{
    const res=await  ordersUpdatePrice({id:state.updatePrice.id,order_amount:state.updatePrice.order_amount})
    showPrice.value=false;
    getLists()
}
const handleLikn = async (row :any)=>{
    const res=await  ordersLooklink({id:row.id})
    linkUrl.value=res.url
    showLink.value=true;
}

const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await ordersDelete({ id })
    getLists()
}

const handleClose = async (id: number) => {
    await feedback.confirm('确定要关闭此订单？')
    await ordersClose({ id })
    getLists()
}

onActivated(() => {
    getLists()
})

getLists()
</script>
<style>
    .dialogbtn{
        margin-top: 30px;
        text-align: center;
    }
</style>
