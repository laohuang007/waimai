<template>
  <div>
    
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
    <!-- 开启底部安全区适配 -->
    <van-number-keyboard safe-area-inset-bottom />
    <van-dialog v-model:show="showD"  ></van-dialog>
  </div>
</template>

<script lang="ts" setup>
import { reactive,ref } from 'vue';
import {config,details,paypal}from '@/api/app'
import router from '@/router';
import { showDialog  } from 'vant';

// console.log(router.currentRoute.value.params.gid);

  const goods_id=ref("")
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
        shop_color:"#000",
        shop_logo:"https://api.pay.ttcni.top/resource/image/adminapi/default/shop_logo.png",
        shop_name:"waimai",
        shop_text_pay:"支付方式",
        shop_text_submit:"立即支付",
        shop_text_nopay:"请选择支付方式",
        shop_text_payin:"正在跳转支付...",
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
      loading.value=true;
      if(state.detail.pay_url){
        window.location.replace(state.detail.pay_url);
        return;
      }
      paypal({id:state.detail.id,payid:state.paychecked}).then((res:any)=>{

        window.location.replace(res.url);
        // loading.value=false;
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
      goods_id.value=router.currentRoute.value.params.gid;
      console.log(goods_id)
      getConfig()
      getDetails()
  }
  onActivated()
</script>

<style lang="scss">
  .center{
    background-color: #f3f4f6;
    min-height: 100vh;
    .btn{
      position: fixed;
      bottom: 50px;
      width: 100%;
      left: 0;
      &-an{
        width: 80%;
        margin: 0 auto;
      }
    }
    .goods{
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 18px 1px #c6ccdf29;
      margin: 16px 16px 0;
      padding: 16px;
      &-title{
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 12px;
      }
      &-content{
        align-items: center;
        display: flex;
        &-right{
          flex: 1;
          margin-left: 12px;
          min-height: 65px;
          position: relative;
          padding-bottom: 35px;
          &-title{
            display: -webkit-box;
            font-size: 1rem;
            line-height: 1.4;
            margin-bottom: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            word-wrap: break-word;
            white-space: normal !important;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
          }
          &-price{
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            span{
              padding-right: 5px;
            }
            &-hx{
              text-decoration:line-through;
            }
            &-sj{
              &-red{
                color: #ff0000;
                font-weight: 550;
                font-size: 1.2rem;
              }
            }
          }
        }
      }
    }
    .paylist{
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 18px 1px #c6ccdf29;
      margin: 16px 16px 0;
      overflow: hidden;
    }
  }
  .loading{
    position: fixed;
    width: 100vw;
    height: 100vh;
    display: flex;
    top: 0;
    left: 0;
    align-items: center;
    justify-content: center;
    background: radial-gradient(#666, transparent);
  }
</style>
