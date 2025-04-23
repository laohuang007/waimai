<template>
    <div class="goods-lists">
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item class="w-[280px]" label="标题">
                    <el-input
                        v-model="queryParams.name"
                        placeholder="输入标题"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="商品原始ID">
                    <el-input
                        v-model="queryParams.gid"
                        placeholder="商品原始ID"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item class="w-[280px]" label="用户UID">
                    <el-input
                        v-model="queryParams.uid"
                        placeholder="输入用户uid"
                        clearable
                        @keyup.enter="resetPage"
                    />
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
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large" v-loading="pager.loading" :data="pager.lists" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" />
                <el-table-column label="ID" prop="id" min-width="80" />
                <el-table-column label="商品原始ID" prop="gid" min-width="80" />
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
                <el-table-column label="归属用户"  min-width="180" >
                    <template #default="{ row }">
                        {{ row.user_name }}（{{ row.uid }}）    
                    </template>
                </el-table-column>
                <el-table-column
                    label="标题"
                    prop="name"
                    min-width="160"
                    show-tooltip-when-overflow
                />
                <el-table-column label="商品价格"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.amount }} {{ row.fee_type }}
                    </template>
                </el-table-column>
                <el-table-column label="商品价格"  min-width="120" >
                    <template #default="{ row }">
                        {{ row.reality_amount }} {{ row.fee_type }}
                    </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" min-width="100" />
                <el-table-column label="修改时间" prop="update_time" min-width="120" />
                <el-table-column label="修改人" prop="update_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="120" />
                <el-table-column label="操作" width="200" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['waimai.userGoods/addOrder']"
                            type="primary"
                            link
                            @click="handleOrder(row)"
                        >
                            创建订单
                        </el-button>
                        <el-button
                            v-perms="['waimai.userGoods/edit', 'waimai.userGoods/add:edit']"
                            type="primary"
                            link
                            @click="handleEdit(row)"
                        >
                            编辑
                        </el-button>
                        <el-button
                            v-perms="['waimai.userGoods/huifu']"
                            type="primary"
                            link
                            @click="handleHuifu(row.id)"
                        >
                            重置
                        </el-button>
                        <el-button
                            v-perms="['waimai.userGoods/delete']"
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
        <order-popup v-if="showOrder" ref="orderRef" @success="orderSuccess" @close="showEdit = false" />
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
<script lang="ts" setup name="goodsLists">
import { userGoodsDelete, userGoodsLists,userGoodsHuifu } from '@/api/waimai/goods'
import { useDictOptions } from '@/hooks/useDictOptions'
import { usePaging } from '@/hooks/usePaging'
import { getRoutePath } from '@/router'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import OrderPopup from './order.vue'

const orderRef = shallowRef<InstanceType<typeof OrderPopup>>()
const showOrder = ref(false)
const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const showEdit = ref(false)
const multipleSelection = ref([])
const showLink = ref(false)
const linkUrl = ref("")

const handleSelectionChange = (val:[]) => {
  multipleSelection.value = val
}

const queryParams = reactive({
    name: '',
    gid: '',
    uid: '',
    status: ''
})

const orderSuccess=(data:any)=>{
    // console.log(data)
    linkUrl.value=data.url;
    showLink.value=true
    orderRef.value?.close()
}
const handleOrder=async (data:any)=>{
    showOrder.value = true
    await nextTick()
    orderRef.value?.open()
    orderRef.value?.getDetail(data)
}
const handleEdit=async(data:any)=>{
    showEdit.value = true
    await nextTick()
    editRef.value?.open()
    editRef.value?.getDetail(data)
}
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: userGoodsLists,
    params: queryParams
})




const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await userGoodsDelete({ id })
    getLists()
}
const handleHuifu = async (id: number) => {
    await feedback.confirm('确定要恢复？')
    await userGoodsHuifu({ id })
    getLists()
}

onActivated(() => {
    getLists()
})

getLists()
</script>
