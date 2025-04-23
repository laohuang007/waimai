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
                <!-- 择操作选择框 -->
                <el-form-item label="状态" prop="action">
                    <el-select
                        v-model="formData.action"
                        class="flex-1"
                        placeholder="请选择操作状态"
                        clearable
                    >
                        <el-option label="减少" :value="2" />
                        <el-option label="增加" :value="1" />
                    </el-select>
                </el-form-item>
                <!-- 金额输入框 -->
                <el-form-item label="金额" prop="num">
                    <el-input
                        v-model="formData.num"
                        placeholder="请输入变动金额"
                        clearable
                    />
                </el-form-item>
                <!-- 备注输入框 -->
                <el-form-item label="备注" prop="remark">
                    <el-input
                        v-model="formData.remark"
                        placeholder="请输入变动金额"
                        :autosize="{ minRows: 2, maxRows: 4 }"
                        type="textarea"
                        clearable
                    />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'

import {  adminMoney } from '@/api/perms/admin'
import Popup from '@/components/popup/index.vue'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const popupTitle = ref('操作余额');
const formData = reactive({
    id: '',
    action: 2,
    num: '',
    remark: '',
})
const formRules = reactive({
    action: [
        {
            required: true,
            message: '请选择操作状态',
            trigger: ['blur']
        }
    ],
    num: [
        {
            required: true,
            message: '请输入变动金额',
            trigger: ['blur']
        }
    ]
    
})

const handleSubmit = async () => {
    await formRef.value?.validate()
    await adminMoney(formData)
    popupRef.value?.close()
    emit('success')
}

const open = () => {
    popupRef.value?.open()
}

const setFormData = async (row: any) => {
    formData.id = row.id;
    popupTitle.value = row.name+'-'+'操作余额';
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData
})
</script>
