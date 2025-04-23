<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
            center
        >
        <el-form
                ref="formRef"
                class="ls-form"
                :model="formData"
                label-width="85px"
                :rules="rules"
            >
                <div class="xl:flex">
                    <div>
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
                        <el-form-item label="排序" prop="sort">
                            <div>
                                <el-input-number v-model="formData.sort" :min="0" :max="9999" />
                                <div class="form-tips">默认为0， 数值越大越排前</div>
                            </div>
                        </el-form-item>
                    </div>
                    <div class="xl:ml-20">
                        <el-form-item label="内容" prop="content">
                            <editor v-model="formData.content" :height="667" :width="375" />
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import { adminLists,userGoodsEdit,userGoodsDetail } from '@/api/waimai/goods'
import Popup from '@/components/popup/index.vue'
import { forEach } from 'lodash'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑商品' : '批量分配商品'
})
const loading = ref(false)
const options = ref([])
const formData = reactive({
    id: '',
    name: '',
    image: '',
    amount: '',
    reality_amount: '',
    fee_type: '',
    content: '',
    sort: 0,
    status: 1,
})

const rules = reactive({
    name: [{ required: true, message: '请输入标题', trigger: 'blur' }],
    cid: [{ required: true, message: '请选择分类', trigger: 'blur' }],
    image: [{ required: true, message: '请上传商品图片', trigger: 'blur' }],
    amount: [{ required: true, message: '请填写商品价格', trigger: 'blur' }],
    reality_amount: [{ required: true, message: '请填写商品实际价格', trigger: 'blur' }],
})
const remoteMethod =async (query: string) => {
    loading.value = true
    const data=await adminLists({keyword:query,page_size:50});
    loading.value = false
    options.value=data.lists
}
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data=await userGoodsEdit(formData);
    // mode.value == 'edit' ? await goodsCateEdit(formData) : await goodsCateAdd(formData)
    popupRef.value?.close()
    emit('success')
}

const open = () => {
    popupRef.value?.open()
}
const getDetail = async (row: Record<string, any>) => {
    const data = await userGoodsDetail({
        id: row.id
    })
    Object.keys(formData).forEach((key) => {
        //@ts-ignore
        formData[key] = data[key]
    })
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    getDetail
})
</script>
