<template>
    <div class="workbench">
        <div class="lg:flex">
            <el-card class="!border-none mb-4 flex-1" shadow="never">
                <template #header>
                    <div>
                        <span class="card-title">今日数据</span>
                        <span class="text-tx-secondary text-xs ml-4">
                            更新时间：{{ today.time }}
                        </span>
                        <div class="float-right">
                            <el-button :icon="Refresh" circle @click="getToday" />
                        </div>
                    </div>
                </template>

                <div class="flex flex-wrap text-center">
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-10">订单总数量</div>
                        <div class="text-6xl">{{ today.order_num  }}</div>
                        <div class="leading-10">订单总金额</div>
                        <div class="text-6xl">{{ today.order_price  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-10">成功数量</div>
                        <div class="text-6xl">{{ today.order_cg_num  }}</div>
                        <div class="leading-10">成功金额</div>
                        <div class="text-6xl">{{ today.order_cg_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-10">失败数量</div>
                        <div class="text-6xl">{{ today.order_sb_num  }}</div>
                        <div class="leading-10">失败金额</div>
                        <div class="text-6xl">{{ today.order_sb_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-10">未支付数量</div>
                        <div class="text-6xl">{{ today.order_wzf_num  }}</div>
                        <div class="leading-10">未支付金额</div>
                        <div class="text-6xl">{{ today.order_wzf_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-10">手续费</div>
                        <div class="text-6xl">{{ today.order_cg_sxf  }}</div>
                        <div class="leading-10">到账金额</div>
                        <div class="text-6xl">{{ today.order_cg_sjje  }}</div>
                    </div>
                    
                </div>
            </el-card>
        </div>
        <div class="function mb-4">
            <el-card class="flex-1 !border-none" shadow="never">
                <template #header>
                    <span>功能</span>
                </template>
                <div class="flex flex-wrap mb-[30px] ">
                    <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                        <el-form-item class="w-[280px]" label="用户ID">
                            <el-input
                                v-model="queryParams.uid"
                                placeholder="用户ID"
                                clearable
                            />
                        </el-form-item>
                        <el-form-item class="w-[280px]" label="支付ID">
                            <el-input
                                v-model="queryParams.payid"
                                placeholder="支付ID"
                                clearable
                            />
                        </el-form-item>
                        <el-form-item class="w-[280px]" label="商品原始ID">
                            <el-input
                                v-model="queryParams.gid"
                                placeholder="商品原始ID"
                                clearable
                            />
                        </el-form-item>
                        <el-form-item class="w-[280px]" label="支付人ID">
                            <el-input
                                v-model="queryParams.payer_id"
                                placeholder="支付人ID"
                                clearable
                            />
                        </el-form-item>
                        <el-form-item class="w-[280px]" label="支付人邮箱">
                            <el-input
                                v-model="queryParams.payer_email"
                                placeholder="支付人邮箱"
                                clearable
                            />
                        </el-form-item>
                        <el-form-item class="w-[280px]" label="时间">
                            <el-date-picker
                                v-model="queryParams.time"
                                type="daterange"
                                unlink-panels
                                range-separator="To"
                                start-placeholder="开始时间"
                                end-placeholder="结束时间"
                                format="YYYY-MM-DD"
                                value-format="YYYY-MM-DD"
                                :shortcuts="shortcuts"
                            />
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="resetPage">查询</el-button>
                            <el-button @click="resetParams">重置</el-button>
                        </el-form-item>
                    </el-form>
                </div>
                <div class="flex flex-wrap mb-[30px] text-center">
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-5">订单总数量</div>
                        <div class="text-3xl">{{ dataInfo.count.order_num  }}</div>
                        <div class="leading-5">订单总金额</div>
                        <div class="text-3xl">{{ dataInfo.count.order_price  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-5">成功数量</div>
                        <div class="text-3xl">{{ dataInfo.count.order_cg_num  }}</div>
                        <div class="leading-5">成功金额</div>
                        <div class="text-3xl">{{ dataInfo.count.order_cg_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-5">失败数量</div>
                        <div class="text-3xl">{{ dataInfo.count.order_sb_num  }}</div>
                        <div class="leading-5">失败金额</div>
                        <div class="text-3xl">{{ dataInfo.count.order_sb_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-5">未支付数量</div>
                        <div class="text-3xl">{{ dataInfo.count.order_wzf_num  }}</div>
                        <div class="leading-5">未支付金额</div>
                        <div class="text-3xl">{{ dataInfo.count.order_wzf_num  }}</div>
                    </div>
                    <div class="w-1/3 md:w-1/5">
                        <div class="leading-5">手续费</div>
                        <div class="text-3xl">{{ dataInfo.count.order_cg_sxf  }}</div>
                        <div class="leading-5">到账金额</div>
                        <div class="text-3xl">{{ dataInfo.count.order_cg_sjje  }}</div>
                    </div>
                    
                </div>
                <el-table :data="dataInfo.list" stripe style="width: 100%">
                    <el-table-column prop="time" label="时间" min-width="180" />
                    <el-table-column label="订单总数量/总金额" min-width="180">
                        <template #default="{ row }">
                            {{ row.order_num }}/{{ row.order_price }}
                        </template>
                    </el-table-column>
                    <el-table-column label="成功数量/金额" min-width="180">
                        <template #default="{ row }">
                            {{ row.order_cg_num }}/{{ row.order_cg_price }}
                        </template>
                    </el-table-column>
                    <el-table-column label="失败数量/金额" min-width="180">
                        <template #default="{ row }">
                            {{ row.order_sb_num }}/{{ row.order_sb_price }}
                        </template>
                    </el-table-column>
                    <el-table-column label="未支付数量/金额" min-width="180">
                        <template #default="{ row }">
                            {{ row.order_wzf_num }}/{{ row.order_wzf_price }}
                        </template>
                    </el-table-column>
                    <el-table-column label="手续费/到账金额" min-width="180">
                        <template #default="{ row }">
                            {{ row.order_cg_sxf }}/{{ row.order_cg_sjje }}
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
        </div>
        
       
        <div class="lg:flex gap-4">
        </div>
    </div>
</template>

<script lang="ts" setup name="workbench">
import vCharts from 'vue-echarts'

import { dataInfoToday,dataInfoLists } from '@/api/waimai/orders'
import useSettingStore from '@/stores/modules/setting'
import { useComponentRef } from '@/utils/getExposeType'
import { calcColor } from '@/utils/util'
import {
    Refresh
} from '@element-plus/icons-vue'

const shortcuts = reactive([
  {
    text: '最近7天',
    value: () => {
      const end = new Date()
      const start = new Date()
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
      return [start, end]
    },
  },
  {
    text: '最近一月',
    value: () => {
      const end = new Date()
      const start = new Date()
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
      return [start, end]
    },
  },
  {
    text: '最近3月',
    value: () => {
      const end = new Date()
      const start = new Date()
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
      return [start, end]
    },
  },
])
const resetPage=()=>{
    console.log(queryParams)
    getDataList()
}
const resetParams=()=>{
    queryParams.uid="";
    queryParams.payid="";
    queryParams.time="";
    queryParams.gid="";
    queryParams.payer_id="";
    queryParams.payer_email="";
    console.log(queryParams)
    getDataList()
}
// 表单数据
const queryParams: any = reactive({
    uid:"",
    payid:"",
    time:"",
    gid:"",
    payer_id:"",
    payer_email:"",
})
// // 今日数据
const today: any = ref({
    time:"2025-04-23 15:15:00",
    order_num:0,
    order_price:0,
    order_cg_num:0,
    order_cg_price:0,
    order_cg_sxf:0,
    order_cg_sjje:0,
    order_sb_num:0,
    order_sb_price:0,
    order_wzf_num:0,
    order_wzf_price:0,
})
// 表单数据
const dataInfo: any = ref({
    list:[
        {
            time:"2025-04-23 15:15:00",
            order_num:0,
            order_price:0,
            order_cg_num:0,
            order_cg_price:0,
            order_cg_sxf:0,
            order_cg_sjje:0,
            order_sb_num:0,
            order_sb_price:0,
            order_wzf_num:0,
            order_wzf_price:0,
        }
    ],
    count:{
        order_num:0,
        order_price:0,
        order_cg_num:0,
        order_cg_price:0,
        order_cg_sxf:0,
        order_cg_sjje:0,
        order_sb_num:0,
        order_sb_price:0,
        order_wzf_num:0,
        order_wzf_price:0,
    }
})

const getToday=async ()=>{
    const data=await dataInfoToday();
    today.value=data;
    console.log(data)
}

const getDataList=async ()=>{
    const data=await dataInfoLists(queryParams);
    dataInfo.value=data;
    console.log(data)
}
onMounted(() => {
    // getData()
    getToday()
    getDataList()
})
</script>

<style lang="scss" scoped></style>
