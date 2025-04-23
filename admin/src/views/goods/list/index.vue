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
                <el-form-item class="w-[280px]" label="分类名称">
                    <el-select v-model="queryParams.cid">
                        <el-option label="全部" value />
                        <el-option
                            v-for="item in optionsData.goods_cate"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
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
        <el-card class="!border-none mt-4" shadow="never">
            <div>
                <div class="float-right">
                <router-link
                    v-perms="['waimai.goods/add', 'waimai.goods/add:edit']"
                    :to="{
                        path: getRoutePath('waimai.goods/add:edit')
                    }"
                >
                    <el-button type="primary" class="mb-4">
                        <template #icon>
                            <icon name="el-icon-Plus" />
                        </template>
                        新增
                    </el-button>
                </router-link>
                </div>
                <el-button type="primary" class="mb-4" 
                    v-perms="['waimai.goods/fenpei']"
                    :disabled="multipleSelection.length<=0"
                    @click="handleAllFenpei"
                >
                   批量分配
                </el-button>
            </div>
            <el-table size="large" v-loading="pager.loading" :data="pager.lists" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" />
                <el-table-column label="ID" prop="id" min-width="80" />
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
                    label="标题"
                    prop="name"
                    min-width="160"
                    show-tooltip-when-overflow
                />
                <el-table-column label="分类" prop="cate_name" min-width="100" />
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
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <el-switch
                            v-perms="['waimai.goods/updateStatus']"
                            v-model="row.status"
                            :active-value="1"
                            :inactive-value="0"
                            @change="changeStatus($event, row.id)"
                        />
                    </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" min-width="100" />
                <el-table-column label="修改时间" prop="update_time" min-width="120" />
                <el-table-column label="修改人" prop="update_name" min-width="120" />
                <el-table-column label="创建时间" prop="create_time" min-width="120" />
                <el-table-column label="创建人" prop="create_name" min-width="120" />
                <el-table-column label="操作" width="200" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['waimai.goods/fenpei']"
                            type="primary"
                            link
                            @click="handleFenpei(row)"
                        >
                            分配
                        </el-button>
                        <el-button
                            v-perms="['waimai.goods/edit', 'waimai.goods/add:edit']"
                            type="primary"
                            link
                        >
                            <router-link
                                :to="{
                                    path: getRoutePath('waimai.goods/add:edit'),
                                    query: {
                                        id: row.id
                                    }
                                }"
                            >
                                编辑
                            </router-link>
                        </el-button>
                        <el-button
                            v-perms="['waimai.goods/delete']"
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
        <fenpei-popup v-if="showFenpei" ref="fenpeiRef" @success="getLists" @close="showFenpei = false" />
    </div>
</template>
<script lang="ts" setup name="goodsLists">
import { goodsCateAll, goodsDelete, goodsLists, goodsStatus } from '@/api/waimai/goods'
import { useDictOptions } from '@/hooks/useDictOptions'
import { usePaging } from '@/hooks/usePaging'
import { getRoutePath } from '@/router'
import feedback from '@/utils/feedback'
import FenpeiPopup from './fenpei.vue'

const fenpeiRef = shallowRef<InstanceType<typeof FenpeiPopup>>()
const showFenpei = ref(false)
const multipleSelection = ref([])

const handleSelectionChange = (val:[]) => {
  multipleSelection.value = val
}

const queryParams = reactive({
    name: '',
    cid: '',
    status: ''
})

const handleFenpei=async (data:any)=>{
    showFenpei.value = true
    await nextTick()
    fenpeiRef.value?.open('one')
    fenpeiRef.value?.getDetail(data)
}
const handleAllFenpei=async()=>{
    showFenpei.value = true
    await nextTick()
    fenpeiRef.value?.open('all')
    fenpeiRef.value?.getDetail(multipleSelection.value)
    // console.log(multipleSelection.value)
}
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: goodsLists,
    params: queryParams
})

const { optionsData } = useDictOptions<{
    goods_cate: any[]
}>({
    goods_cate: {
        api: goodsCateAll
    }
})

const changeStatus = async (status: any, id: number) => {
    try {
        await goodsStatus({ id, status })
        getLists()
    } catch (error) {
        getLists()
    }
}

const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await goodsDelete({ id })
    getLists()
}

onActivated(() => {
    getLists()
})

getLists()
</script>
