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
                <el-form-item label="充值类型" prop="pay_type">
                    <el-select v-model="formData.pay_type">
                        <el-option label="USD" :value="1" />
                        <el-option label="银行卡" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item label="充值金额" prop="order_amount">
                    <div>
                        <el-input-number v-model="formData.order_amount" :min="0" />
                    </div>
                </el-form-item>
                <el-form-item label="凭证图片" prop="image">
                    <div>
                        <div>
                            <material-picker v-model="formData.image" :limit="1" />
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import {  rechargeAdd } from '@/api/waimai/orders'
import Popup from '@/components/popup/index.vue'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑' : '新增'
})
const formData = reactive({
    id: '',
    order_amount: 0,
    image: "",
    pay_type: 1
})

const formRules = {
    order_amount: [
        {
            required: true,
            message: '请输入充值金额',
            trigger: ['blur']
        }
    ]
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    mode.value == await rechargeAdd(formData) 
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
