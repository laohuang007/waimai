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

            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import { adminLists,goodsFenpei } from '@/api/waimai/goods'
import Popup from '@/components/popup/index.vue'
import { forEach } from 'lodash'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')
const popupTitle = computed(() => {
    return mode.value == 'one' ? '分配商品' : '批量分配商品'
})
const loading = ref(false)
const options = ref([])
const formData = reactive({
    uid: '',
    type: 'one',
    gid:"",
    gidList:[]
})

const formRules = {
    uid: [
        {
            required: true,
            message: '请选择要分配的用户',
            trigger: ['blur']
        }
    ]
}
const remoteMethod =async (query: string) => {
    loading.value = true
    const data=await adminLists({keyword:query,page_size:50});
    loading.value = false
    options.value=data.lists
}
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data=await goodsFenpei(formData);
    // mode.value == 'edit' ? await goodsCateEdit(formData) : await goodsCateAdd(formData)
    popupRef.value?.close()
    emit('success')
}

const open = (type = 'one') => {
    mode.value = type
    formData.type=type
    popupRef.value?.open()
}
const getDetail = async (row: Record<string, any>) => {
    if(formData.type=='one'){
        formData.gid=row.id
    }else{
        let id=[];
        row.forEach((item:any)=>{
            id.push(item.id)
        })
        formData.gidList=id;
    }
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    getDetail
})
</script>
