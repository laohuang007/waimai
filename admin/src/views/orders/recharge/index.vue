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
                <el-form-item class="w-[280px]" label="备注信息">
                    <el-input
                        v-model="queryParams.remark"
                        placeholder="备注信息"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="审核人ID">
                    <el-input
                        v-model="queryParams.update_by"
                        placeholder="输入审核人ID"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="创建者ID">
                    <el-input
                        v-model="queryParams.create_by"
                        placeholder="创建者ID"
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
                <el-form-item class="w-[280px]" label="ip">
                    <el-input
                        v-model="queryParams.ip"
                        placeholder="输入ip"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="状态">
                    <el-select v-model="queryParams.status">
                        <el-option label="全部" value />
                        <el-option label="审核中" :value="0" />
                        <el-option label="审核成功" :value="1" />
                        <el-option label="审核失败" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item class="w-[280px]" label="类型">
                    <el-select v-model="queryParams.pay_type">
                        <el-option label="全部" value />
                        <el-option label="USD" :value="1" />
                        <el-option label="银行卡" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                    <export-data
                        class="ml-2.5"
                        :fetch-fun="rechargeLists"
                        :params="queryParams"
                        :page-size="pager.size"
                    />
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <div>
                <div class="float-right" v-perms="['waimai.recharge/add']">
                    <el-button type="primary" @click="handleAdd" class="mb-4">
                        <template #icon>
                            <icon name="el-icon-Plus" />
                        </template>
                        新增
                    </el-button>
                </div>
            </div>
            <el-table size="large"  v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="订单号" prop="order_sn" min-width="220" />
                <el-table-column label="用户昵称"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.user_name }}({{ row.uid }})
                    </template>
                </el-table-column>
                <el-table-column label="支付类型" prop="pay_type_text" min-width="100" />
                <el-table-column label="审核状态" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="state.statusText[row.status]?state.statusText[row.status].type:'primary'">{{state.statusText[row.status]?state.statusText[row.status].text:'未知'}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="审核人" prop="update_name" min-width="120" />
                <el-table-column label="审核时间" prop="pay_time" min-width="150" />
                <el-table-column label="充值金额" prop="order_amount" min-width="100" />
                <el-table-column label="汇率" prop="rate" min-width="100" />
                <el-table-column label="手续费" prop="service_charge" min-width="100" />
                <el-table-column label="到账金额" prop="reality_amount" min-width="100" />
                <el-table-column label="IP" prop="ip" min-width="220" />
                <el-table-column label="备注" prop="remark" min-width="220" />
                <el-table-column label="创建人" prop="create_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="150" />
                <el-table-column label="操作" width="230" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['waimai.recharge/updateStatus']"
                            type="primary"
                            link
                            :disabled="row.status!=0"
                            @click="handleShenhe(row,1)"
                        >
                            审核成功
                        </el-button>
                        <el-button
                            v-perms="['waimai.recharge/updateStatus']"
                            type="primary"
                            link
                            :disabled="row.status!=0"
                            @click="handleShenhe(row,2)"
                        >
                            审核失败
                        </el-button>
                        <el-button
                            v-perms="['waimai.recharge/delete']"
                            type="danger"
                            link
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
        <el-dialog v-model="showShenhe" :show-close="false" width="500">
            <template #header="{ close, titleId, titleClass }">
            <div class="my-header">
                <h4 :id="titleId" :class="titleClass">{{state.upData.status==1?'审核成功':'审核失败'}}
                    <el-button type="danger" class="float-right" @click="showShenhe=false">
                        <el-icon class="el-icon--left"><CircleCloseFilled /></el-icon>
                        关闭
                    </el-button>
                </h4>
            </div>
            </template>
            <div>
                <el-form-item label="汇率" v-if="state.upData.status==1">
                    <el-input v-model="state.upData.rate" placeholder="请输入汇率" />
                </el-form-item>
                <el-form-item label="备注">
                    <el-input v-model="state.upData.remark" type="textarea" placeholder="请输入备注" />
                </el-form-item>
            </div>
            <div class="dialogbtn">
                <el-button type="primary" @click="priceSb">确定</el-button>
            </div>
        </el-dialog>
        <edit-popup v-if="showEdit" ref="editRef" @success="getLists" @close="showEdit = false" />
        
    </div>
</template>
<script lang="ts" setup name="rechargeLists">
import {  rechargeDelete,rechargeLists, rechargeUpdateStatus } from '@/api/waimai/orders'
import { useDictOptions } from '@/hooks/useDictOptions'
import { usePaging } from '@/hooks/usePaging'
import { getRoutePath } from '@/router'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const multipleSelection = ref([])

const handleSelectionChange = (val:[]) => {
  multipleSelection.value = val
}

const showEdit = ref(false)
const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const linkUrl = ref("")
const showShenhe = ref(false)

const state = reactive({
    statusText:[
        {"text":"待审核","type":"info"},
        {"text":"已审核","type":"success"},
        {"text":"审核失败","type":"danger"}
    ],
    upData:{
        id:"",
        status:0,
        rate:0,
        remark:""
    }
})
const queryParams = reactive({
    ip: '',
    remark: '',
    order_sn: '',
    uid: '',
    pay_type: '',
    status: '',
    update_by: '',
    create_by: '',
})
const handleEdit=async(data:any)=>{
    showEdit.value = true
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.getDetail(data)
}
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: rechargeLists,
    params: queryParams
})

const handleShenhe = async (row :any,type:number)=>{
    state.upData={
        id:row.id,
        rate:0,
        status:type,
        remark:''
    };
    showShenhe.value=true;
}
const priceSb = async ()=>{
    const res=await  rechargeUpdateStatus(state.upData)
    showShenhe.value=false;
    getLists()
}


const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await rechargeDelete({ id })
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
