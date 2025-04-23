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
            <el-form ref="formRef"  :model="formData" label-width="120px" :rules="formRules">
                <el-form-item label="分配的用户" prop="uid">
                    <el-select
                        v-model="formData.uid"
                        filterable
                        remote
                        reserve-keyword
                        placeholder="请输入关键字进行搜索"
                        remote-show-suffix
                        :remote-method="remoteMethod"
                        :loading="loading"
                        style="width: 240px"
                    >
                        <el-option
                        v-for="item in options"
                        :key="item.id"
                        :label="item.name+'('+item.account+')'"
                        :value="item.id"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item label="费率" prop="rate">
                    <el-input v-model="formData.rate" placeholder="请输入费率" clearable />
                </el-form-item>

            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import { adminLists,paypalFenpei } from '@/api/waimai/paypal'
import Popup from '@/components/popup/index.vue'
import { forEach } from 'lodash'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return '分配通道'
})
const loading = ref(false)
const options = ref([])
const formData = reactive({
    uid: '',
    payid:"",
    rate:"",
})

const formRules = {
    uid: [
        {
            required: true,
            message: '请选择要分配的用户',
            trigger: ['blur']
        }
    ],
    rate: [
        {
            required: true,
            message: '请输入费率',
            trigger: ['blur']
        }
    ],
}
const remoteMethod =async (query: string) => {
    loading.value = true
    const data=await adminLists({keyword:query,page_size:50});
    loading.value = false
    options.value=data.lists
}
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data=await paypalFenpei(formData);
    // mode.value == 'edit' ? await goodsCateEdit(formData) : await goodsCateAdd(formData)
    popupRef.value?.close()
    emit('success')
}

const open = (row: Record<string, any>) => {
    formData.payid=row.id
    popupRef.value?.open()
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open
})
</script>
