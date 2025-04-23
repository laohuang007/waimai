<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-alert
                type="warning"
                title="温馨提示：用于管理网站的分类，只可添加到一级"
                :closable="false"
                show-icon
            />
        </el-card>
        <el-card class="!border-none mt-4" shadow="never" v-loading="pager.loading">
            <div>
                <el-button
                    class="mb-4"
                    v-perms="['waimai.goodsCate/add']"
                    type="primary"
                    @click="handleAdd()"
                >
                    <template #icon>
                        <icon name="el-icon-Plus" />
                    </template>
                    新增
                </el-button>
            </div>
            <el-table size="large" :data="pager.lists">
                <el-table-column label="商品名称" prop="name" min-width="120" />
                <el-table-column label="商品数" prop="goods_count" min-width="120" />
                <el-table-column label="状态" min-width="120">
                    <template #default="{ row }">
                        <el-switch
                            v-perms="['waimai.goodsCate/updateStatus']"
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
                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['waimai.goodsCate/edit']"
                            type="primary"
                            link
                            @click="handleEdit(row)"
                        >
                            编辑
                        </el-button>
                        <el-button
                            v-perms="['waimai.goodsCate/delete']"
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
import { goodsCateDelete, goodsCateLists, goodsCateStatus } from '@/api/waimai/goods'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'

import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const showEdit = ref(false)

const { pager, getLists } = usePaging({
    fetchFun: goodsCateLists
})
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
    await goodsCateDelete({ id })
    getLists()
}

const changeStatus = async (status: any, id: number) => {
    try {
        await goodsCateStatus({ id, status })
        getLists()
    } catch (error) {
        getLists()
    }
}

getLists()
</script>
