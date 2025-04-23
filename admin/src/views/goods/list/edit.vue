<template>
    <div class="goods-edit">
        <el-card class="!border-none" shadow="never">
            <el-page-header :content="$route.meta.title" @back="$router.back()" />
        </el-card>
        <el-card class="mt-4 !border-none" shadow="never">
            <el-form
                ref="formRef"
                class="ls-form"
                :model="formData"
                label-width="85px"
                :rules="rules"
            >
                <div class="xl:flex">
                    <div>
                        <el-form-item label="分类" prop="cid">
                            <el-select
                                class="w-80"
                                v-model="formData.cid"
                                placeholder="请选择分类"
                                clearable
                            >
                                <el-option
                                    v-for="item in optionsData.goods_cate"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id"
                                />
                            </el-select>
                        </el-form-item>
                        <el-form-item label="标题" prop="name">
                            <div class="w-80">
                                <el-input
                                    v-model="formData.name"
                                    placeholder="请输入标题"
                                    type="textarea"
                                    :autosize="{ minRows: 3, maxRows: 3 }"
                                    maxlength="64"
                                    show-word-limit
                                    clearable
                                />
                            </div>
                        </el-form-item>
                        <el-form-item label="封面" prop="image">
                            <div>
                                <div>
                                    <material-picker v-model="formData.image" :limit="1" />
                                </div>
                                <div class="form-tips">建议尺寸：240*180px</div>
                            </div>
                        </el-form-item>
                        <el-form-item label="商品价格" prop="amount">
                            <div class="w-80">
                                <el-input v-model="formData.amount" placeholder="请输入商品价格" />
                            </div>
                        </el-form-item>
                        <el-form-item label="实际价格" prop="reality_amount">
                            <div class="w-80">
                                <el-input v-model="formData.reality_amount" placeholder="请输入商品实际价格" />
                            </div>
                        </el-form-item>
                        <el-form-item label="价格单位" required prop="fee_type">
                            <el-select
                                class="w-80"
                                v-model="formData.fee_type"
                                placeholder="价格单位"
                                clearable
                            >
                                <el-option
                                    label="USD"
                                    value="USD"
                                />
                            </el-select>
                        </el-form-item>
                        <el-form-item label="状态" required prop="status">
                            <el-radio-group v-model="formData.status">
                                <el-radio :value="1">显示</el-radio>
                                <el-radio :value="0">隐藏</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="排序" prop="sort">
                            <div>
                                <el-input-number v-model="formData.sort" :min="0" :max="9999" />
                                <div class="form-tips">默认为0， 数值越大越排前</div>
                            </div>
                        </el-form-item>
                        <el-form-item label="状态" required prop="status">
                            <el-radio-group v-model="formData.status">
                                <el-radio :value="1">显示</el-radio>
                                <el-radio :value="0">隐藏</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </div>
                    <div class="xl:ml-20">
                        <el-form-item label="内容" prop="content">
                            <editor v-model="formData.content" :height="667" :width="375" />
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </el-card>
        <footer-btns>
            <el-button type="primary" @click="handleSave">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="goodsListsEdit">
import type { FormInstance } from 'element-plus'

import { goodsAdd, goodsCateAll, goodsDetail, goodsEdit } from '@/api/waimai/goods'
import { useDictOptions } from '@/hooks/useDictOptions'
import useMultipleTabs from '@/hooks/useMultipleTabs'

const route = useRoute()
const router = useRouter()
const formData = reactive({
    id: '',
    cid: '',
    name: '',
    image: '',
    amount: '',
    reality_amount: '',
    fee_type: '',
    content: '',
    sort: 0,
    status: 1,
})

const { removeTab } = useMultipleTabs()
const formRef = shallowRef<FormInstance>()
const rules = reactive({
    name: [{ required: true, message: '请输入标题', trigger: 'blur' }],
    cid: [{ required: true, message: '请选择分类', trigger: 'blur' }],
    image: [{ required: true, message: '请上传商品图片', trigger: 'blur' }],
    amount: [{ required: true, message: '请填写商品价格', trigger: 'blur' }],
    reality_amount: [{ required: true, message: '请填写商品实际价格', trigger: 'blur' }],
})

const getDetails = async () => {
    const data = await goodsDetail({
        id: route.query.id
    })
    Object.keys(formData).forEach((key) => {
        //@ts-ignore
        formData[key] = data[key]
    })
}

const { optionsData } = useDictOptions<{
    goods_cate: any[]
}>({
    goods_cate: {
        api: goodsCateAll
    }
})

const handleSave = async () => {
    await formRef.value?.validate()
    if (route.query.id) {
        await goodsEdit(formData)
    } else {
        await goodsAdd(formData)
    }
    removeTab()
    router.back()
}

route.query.id && getDetails()
</script>
