<template>
    <div>
        <div v-if="type==5">
            <div class="center" v-if="!gjpload">
                <div class="goods">
                <div class="goods-title">
                    <van-skeleton-paragraph />
                </div>
                <div class="goods-">
                    <van-skeleton>
                    <template #template>
                        <div :style="{ display: 'flex', width: '100%' }">
                        <van-skeleton-image />
                        <div :style="{ flex: 1, marginLeft: '16px' }">
                            <van-skeleton-paragraph row-width="60%" />
                            <van-skeleton-paragraph />
                            <van-skeleton-paragraph />
                            <van-skeleton-paragraph />
                        </div>
                        </div>
                    </template>
                    </van-skeleton>
                </div>
                </div>
            </div>
            <div class="center" v-if="gjpload">
                <div class="goods">
                <div class="goods-title">
                    {{ state.detail.name }}
                </div>
                <div class="goods-content">
                    <div class="goods-content-image">
                        <van-image
                        height="100px"
                        width="100px"
                        fit="contain"
                        :src="state.detail.image"
                        />
                    </div>
                    <div class="goods-content-right">
                        <div class="goods-content-right-title">
                        {{ state.detail.name }}
                        </div>
                        <div  class="goods-content-right-price">
                        <div class="goods-content-right-price-hx">
                            <span>{{ state.detail.amount }}</span>
                            <span>{{ state.detail.fee_type }}</span>
                        </div>
                        <div class="goods-content-right-price-sj">
                            <span class="goods-content-right-price-sj-red">{{ state.detail.order_amount }}</span>
                            <span>{{ state.detail.fee_type }}</span>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="paylist">
                <van-radio-group v-model="state.paychecked">
                    <van-cell-group :title="state.config.shop_text_pay">
                    <van-cell :title="item.name" clickable @click="state.paychecked = item.id"  v-for="item,index in state.detail.pay_list" :key="index">
                        <template #title>
                        <div>
                            <van-image
                            height="30px"
                            fit="contain"
                            :src="item.image"
                            />
                        </div>
                        <div class="goods-price"></div>
                        </template>
                        <template #right-icon>
                        <van-radio :name="item.id" :checked-color="state.config.shop_color" />
                        </template>
                    </van-cell>
                    </van-cell-group>
                </van-radio-group>
                </div>
                <div class="btn">
                <div class="btn-an">
                    <van-button :color="state.config.shop_color" :disabled="loading" block round @click="submit">{{state.config.shop_text_submit}}</van-button>
                </div>
                </div>
            </div>
            <div class="loading" v-if="loading">
                <van-loading size="50px" color="#0094ff" type="spinner"  vertical>{{state.config.shop_text_payin}}</van-loading>
            </div>
            <van-dialog v-model:show="showD"  ></van-dialog>
        </div>
        <div v-if="type==1" class="center">
            <div class="box">
                <van-icon name="checked" size="100px" color="#07c160" />
                <div class="box-text" style="color:#07c160">{{ state.config.shop_text_paysuccess }}</div>
            </div>
        </div>
        <div v-if="type==2" class="center">
            <div class="box">
                <van-icon name="clear" size="100px" color="#F56C6C" />
                <div class="box-text" style="color:#F56C6C">{{ state.config.shop_text_payfail }}</div>
            </div>
        </div>
        <div v-if="type==3" class="center">
            <div class="box">
                <van-icon name="info" size="100px" color="#E6A23C" />
                <div class="box-text" style="color:#E6A23C">{{ state.config.shop_text_paynot }}</div>
            </div>
        </div>
        <!-- 开启底部安全区适配 -->
        <van-number-keyboard safe-area-inset-bottom />
    </div>
  </template>
  
  <script lang="ts" setup>
    import { reactive,ref } from 'vue';
    import {config,details,paypal}from '@/api/app'
    import router from '@/router';
    import { showDialog  } from 'vant';
    import { Locale } from 'vant';

    const messages = {
        'zh-CN': {
        vanPicker: {
            confirm: 'OK', // 将'确认'修改为'关闭'
        },
        },
    };
    Locale.add(messages);
    // console.log(router.currentRoute.value.params.gid);
  
    const goods_id=ref("")
    const type=ref(3)
    const showD=ref(false)
    const gjpload=ref(false)
    const loading=ref(false)
  
    const state = reactive({
        name: '',
        gid: '',
        uid: '',
        paychecked: '',
        config:{
          favicon:"https://api.pay.ttcni.top/resource/image/adminapi/default/web_favicon.ico",
          shop_logo:"https://api.pay.ttcni.top/resource/image/adminapi/default/shop_logo.png",
          shop_name:"waimai",
          shop_color:"#000",
          shop_text_pay:"支付方式",
          shop_text_submit:"立即支付",
          shop_text_nopay:"请选择支付方式",
          shop_text_payin:"正在跳转支付...",
          shop_text_payfail:"支付失败...",
          shop_text_paysuccess:"支付成功",
          shop_text_paynot:"没有数据",
          shop_url:"https://pay.pay.ttcni.top/"
        },
        detail:{
          amount: "11.00",
          create_time: "2025-04-15 16:21:33",
          fee_type: "USD",
          id: 3,
          image: "https://api.pay.ttcni.top/uploads/images/20250409/202504091852458feaa8199.jpg",
          name: "test goods12344",
          order_amount: "10.00",
          order_sn: "",
          pay_list: [],
          pay_url: "",
          payid: 1,
          uid: 2,
          update_time: "2025-04-15 16:21:33"
        }
    })
    const submit=()=>{
      if(state.paychecked){
        if(state.detail.pay_url){
          window.location.replace(state.detail.pay_url);
          return;
        }
        loading.value=true;
        paypal({id:state.detail.id,payid:state.paychecked}).then((res:any)=>{
  
          window.location.replace(res.url);
          loading.value=false;
        })
        .catch(()=>{
          loading.value=false;
        })
      }else{
        showDialog({confirmButtonText:"OK",message:state.config.shop_text_nopay})
      }
    }
    const getConfig =()=>{
        config({}).then((res: any) => {
          // console.log(res, "请求成功")
          state.config=res;
          document.title =res.shop_name
          changeFavicon(res.favicon)
        })
        .catch(()=>{
  
        })
      }
    const getDetails =()=>{
      details({random:goods_id.value}).then((res: any) => {
          console.log(res, "请求成功")
          state.detail=res
          gjpload.value=true
        })
      }
    //修改Favicon的方法
    const changeFavicon = (link:string) => {
      let $favicon = document.querySelector('link[rel="icon"]');
      if ($favicon !== null) {
        $favicon.href = link;
      } else {
        $favicon = document.createElement("link");
        $favicon.rel = "icon";
        $favicon.href = link;
        document.head.appendChild($favicon);
      }
    }
    
    const onActivated=() => {
        console.log(router.currentRoute.value)
        goods_id.value=router.currentRoute.value.params.gid;
        type.value=router.currentRoute.value.params.type?router.currentRoute.value.params.type:3;
        console.log(goods_id,type)
        getConfig()
        if(goods_id.value)
        getDetails()
    }
    onActivated()
</script>
  
  <style lang="scss">
    .center{
        .box{
            width: 100vw;
            height: 100vh;
            display: flex;
            align-content: center;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            &-text{
                line-height: 50px;
                font-weight: 600;
                font-size: 1.5rem;
            }
        }
    }
  </style>
  