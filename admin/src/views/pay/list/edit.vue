<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="84px" :rules="formRules">
                <el-form-item label="支付名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入支付名称" clearable />
                </el-form-item>
                <el-form-item label="支付图片" prop="image">
                    <div>
                        <div>
                            <material-picker v-model="formData.image" :limit="1" />
                        </div>
                        <div class="form-tips">建议尺寸：384*216px</div>
                    </div>
                </el-form-item>
                <el-form-item class="w-[280px]" label="货币单位" prop="fee_type">
                    <el-select v-model="formData.fee_type">
                        <el-option label="USD" value="USD" />
                    </el-select>
                </el-form-item>
                <el-form-item label="CLIENT_ID" prop="client_id">
                    <el-input v-model="formData.client_id" placeholder="请输入CLIENT_ID" clearable />
                </el-form-item>
                <el-form-item label="SECRET_KEY" prop="secret_key">
                    <el-input v-model="formData.secret_key" placeholder="请输入SECRET_KEY" clearable />
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <div>
                        <el-input-number v-model="formData.sort" :min="0" :max="9999" />
                        <div class="form-tips">默认为0， 数值越大越排前</div>
                    </div>
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="formData.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import { paypalAdd, paypalDetail, paypalEdit } from '@/api/waimai/paypal'
import Popup from '@/components/popup/index.vue'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑支付' : '新增支付'
})
const formData = reactive({
    id: '',
    name: '',
    fee_type: 'USD',
    image: '',
    client_id: '',
    secret_key: '',
    sort: 0,
    status: 1
})

const formRules = {
    name: [
        {
            required: true,
            message: '请输入支付名称',
            trigger: ['blur']
        }
    ],
    image: [
        {
            required: true,
            message: '请选择支付图片',
            trigger: ['blur']
        }
    ],
    fee_type: [
        {
            required: true,
            message: '请选择货币单位',
            trigger: ['blur']
        }
    ],
    client_id: [
        {
            required: true,
            message: '请输入client_id',
            trigger: ['blur']
        }
    ],
    secret_key: [
        {
            required: true,
            message: '请输入secret_key',
            trigger: ['blur']
        }
    ],
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    mode.value == 'edit' ? await paypalEdit(formData) : await paypalAdd(formData)
    popupRef.value?.close()
    emit('success')
}

const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
}

const setFormData = (data: Record<any, any>) => {
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }
}

const getDetail = async (row: Record<string, any>) => {
    const data = await paypalDetail({
        id: row.id
    })
    setFormData(data)
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
