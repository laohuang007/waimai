<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item class="w-[280px]" label="支付名称">
                    <el-input
                        v-model="queryParams.name"
                        placeholder="输入标题"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="支付ID">
                    <el-input
                        v-model="queryParams.payid"
                        placeholder="请输入支付ID"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="货币单位">
                    <el-select v-model="queryParams.fee_type">
                        <el-option label="全部" value />
                        <el-option label="USD" value="USD" />
                    </el-select>
                </el-form-item>
                <el-form-item class="w-[280px]" label="状态">
                    <el-select v-model="queryParams.status">
                        <el-option label="全部" value />
                        <el-option label="显示" :value="1" />
                        <el-option label="隐藏" :value="0" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never" v-loading="pager.loading">
            <el-table size="large" :data="pager.lists">
                <el-table-column label="支付ID" prop="payid" min-width="80" />
                <el-table-column label="归属用户"  min-width="180" >
                    <template #default="{ row }">
                        {{ row.user_name }}（{{ row.uid }}）    
                    </template>
                </el-table-column>
                <el-table-column label="支付图片" min-width="100">
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
                <el-table-column label="支付名称" prop="name" min-width="120" />
                <el-table-column label="支付单位" prop="fee_type" min-width="120" />
                <el-table-column label="费率" prop="rate" min-width="120" />
                <el-table-column label="状态" min-width="120"  v-perms="['waimai.paypalUser/updateStatus']">
                    <template #default="{ row }">
                        <el-switch
                            v-model="row.status"
                            :active-value="1"
                            :inactive-value="0"
                            @change="changeStatus($event, row.id)"
                        />
                    </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" min-width="120" />
                <el-table-column label="创建人" prop="create_name" min-width="120" />
                <el-table-column label="修改人" prop="update_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="120" />
                <el-table-column label="修改时间" prop="update_time" min-width="120" />
                <el-table-column label="操作" width="180" fixed="right" v-perms="['waimai.paypal/edit','waimai.paypal/delete']">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['waimai.paypal/edit']"
                            type="primary"
                            link
                            @click="handleEdit(row)"
                        >
                            编辑
                        </el-button>
                        <el-button
                            v-perms="['waimai.paypal/delete']"
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
        <edit-popup v-if="showEdit" ref="editRef" @success="getLists" @close="showEdit = false" />
    </div>
</template>
<script lang="ts" setup name="articleColumn">
import { paypalUserDelete, paypalUserLists, paypalUserStatus } from '@/api/waimai/paypal'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'

import EditPopup from './edit.vue'


const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const showEdit = ref(false)
const queryParams = reactive({
    name: '',
    payid: '',
    fee_type:'',
    status: ''
})
const { pager, getLists,resetPage, resetParams } = usePaging({
    fetchFun: paypalUserLists,
    params: queryParams
})
const handleFenpei=async (data:any)=>{
    showFenpei.value = true
    await nextTick()
    fenpeiRef.value?.open(data)
}
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}

const handleEdit = async (data: any) => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.getDetail(data)
}

const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await paypalUserDelete({ id })
    getLists()
}

const changeStatus = async (status: any, id: number) => {
    try {
        await paypalUserStatus({ id, status })
        getLists()
    } catch (error) {
        getLists()
    }
}

getLists()
</script>
