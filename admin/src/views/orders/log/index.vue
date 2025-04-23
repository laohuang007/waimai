<template>
    <div class="orders-lists">
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item class="w-[280px]" label="流水订单号">
                    <el-input
                        v-model="queryParams.order_sn"
                        placeholder="输入订单号"
                        clearable
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="关联订单号">
                    <el-input
                        v-model="queryParams.source_sn"
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
                <el-form-item class="w-[280px]" label="动作">
                    <el-select v-model="queryParams.action">
                        <el-option label="全部" value />
                        <el-option label="增加" :value="1" />
                        <el-option label="减少" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item class="w-[280px]" label="类型">
                    <el-select v-model="queryParams.change_type">
                        <el-option label="全部" value />
                        <el-option label="充值" :value="1" />
                        <el-option label="提现" :value="2" />
                        <el-option label="订单收款" :value="3" />
                        <el-option label="系统操作" :value="4" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                    <export-data
                        class="ml-2.5"
                        :fetch-fun="accountLogLists"
                        :params="queryParams"
                        :page-size="pager.size"
                    />
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large"  v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="订单号" prop="order_sn" min-width="220" />
                <el-table-column label="用户昵称"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.user_name }}({{ row.uid }})
                    </template>
                </el-table-column>
                <el-table-column label="变动类型" prop="change_type_text" min-width="100" />
                <el-table-column label="变动动作" prop="action_text" min-width="80" />
                <el-table-column label="变动前数量" prop="left_amount" min-width="100" />
                <el-table-column label="变动数量" prop="change_amount" min-width="100" />
                <el-table-column label="变动后数量" prop="right_amount" min-width="100" />
                <el-table-column label="关联单号" prop="source_sn" min-width="220" />
                <el-table-column label="IP" prop="ip" min-width="220" />
                <el-table-column label="备注" prop="remark" min-width="220" />
                <el-table-column label="创建人" prop="create_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="150" />
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
</template>
<script lang="ts" setup name="accountLogLists">
import {  accountLogLists,} from '@/api/waimai/orders'
import { usePaging } from '@/hooks/usePaging'

const queryParams = reactive({
    ip: '',
    remark: '',
    order_sn: '',
    uid: '',
    change_type: '',
    action: '',
    source_sn: '',
    create_by: '',
})
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: accountLogLists,
    params: queryParams
})


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
