<!-- 网站信息 -->
<template>
    <div class="website-information">
        <el-form
            ref="formRef"
            :rules="rules"
            class="ls-form"
            :model="formData"
            label-width="120px"
            scroll-to-error
        >
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">后台设置</div>
                <el-form-item label="网站名称" prop="name">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.name"
                            placeholder="请输入网站名称"
                            maxlength="30"
                            show-word-limit
                        />
                    </div>
                </el-form-item>
                <el-form-item label="网站图标" prop="web_favicon" required>
                    <div>
                        <material-picker v-model="formData.web_favicon" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="网站LOGO" prop="web_logo" required>
                    <div>
                        <material-picker v-model.trim="formData.web_logo" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="登录页广告图" prop="login_image" required>
                    <div>
                        <material-picker v-model.trim="formData.login_image" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="text-xl font-medium mb-[20px]">前台设置</div>
                <el-form-item label="前台名称" prop="shop_name">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_name"
                            placeholder="请输入前台名称"
                            maxlength="30"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="前台域名" prop="shop_url">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_url"
                            placeholder="请输入前台域名"
                            maxlength="128"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="主题颜色" prop="shop_color">
                    <div class="w-80">
                        <el-color-picker v-model="formData.shop_color" />
                    </div>
                </el-form-item>
                <el-form-item label="网站图标" prop="web_favicon" required>
                    <div>
                        <material-picker v-model="formData.h5_favicon" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="前台LOGO" prop="shop_logo">
                    <div>
                        <material-picker v-model="formData.shop_logo" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100px，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <div class="text-xsl font-medium mb-[20px]">文本翻译</div>
                <el-form-item label="支付方式" prop="shop_text_pay">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_pay"
                            placeholder="翻译-支付方式"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="立即支付" prop="shop_text_submit">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_submit"
                            placeholder="翻译-立即支付"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="请选择支付方式" prop="shop_text_nopay">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_nopay"
                            placeholder="翻译-请选择支付方式？"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="跳转支付中" prop="shop_text_payin">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_payin"
                            placeholder="翻译-跳转支付中"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="支付失败" prop="shop_text_payfail">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_payfail"
                            placeholder="翻译-支付失败"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="支付成功" prop="shop_text_paysuccess">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_paysuccess"
                            placeholder="翻译-支付成功"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="没有数据" prop="shop_text_paynot">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.shop_text_paynot"
                            placeholder="翻译-没有数据"
                            show-word-limit
                        ></el-input>
                    </div>
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4" v-if="false">
                <div class="text-xl font-medium mb-[20px]">PC端设置</div>
                <el-form-item label="PC端LOGO" prop="pc_logo">
                    <div>
                        <material-picker v-model="formData.pc_logo" :limit="1" />
                        <div class="form-tips">建议尺寸：120*28px，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="网站标题" prop="pc_title">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.pc_title"
                            placeholder="请输入PC端网站标题"
                            maxlength="30"
                            show-word-limit
                        />
                    </div>
                </el-form-item>
                <el-form-item label="网站图标" prop="pc_ico">
                    <div>
                        <material-picker v-model="formData.pc_ico" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="网站描述" prop="pc_desc">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.pc_desc"
                            placeholder="请输入PC端网站描述"
                        />
                    </div>
                </el-form-item>
                <el-form-item label="网站关键词" prop="pc_keywords">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.pc_keywords"
                            placeholder="请输入PC端网站关键词"
                        />
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.web.web_setting/setWebsite']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="webInformation">
import type { FormInstance } from 'element-plus'

import { getWebsite, setWebsite } from '@/api/setting/website'
import useAppStore from '@/stores/modules/app'

const formRef = ref<FormInstance>()

const appStore = useAppStore()
// 表单数据
const formData = reactive({
    name: '', // 网站名称
    web_favicon: '', // 网站图标
    web_logo: '', // 网站logo
    login_image: '', // 登录页广告图
    h5_favicon: '',
    shop_name: '',
    shop_url: '',
    shop_logo: '',
    pc_logo: '',
    pc_title: '',
    pc_desc: '',
    pc_ico: '',
    pc_keywords: '',
    shop_color:"",
    shop_text_pay:"",
    shop_text_submit:"",
    shop_text_nopay:"",
    shop_text_payin:"",
    shop_text_payfail:"",
    shop_text_paysuccess:"",
    shop_text_paynot:"",
})

// 表单验证
const rules = {
    shop_color: [
        {
            required: true,
            message: '请选择主题颜色',
            trigger: ['blur']
        }
    ],
    shop_text_pay: [
        {
            required: true,
            message: '支付方式-译文',
            trigger: ['blur']
        }
    ],
    shop_text_submit: [
        {
            required: true,
            message: '立即支付-译文',
            trigger: ['blur']
        }
    ],
    shop_text_nopay: [
        {
            required: true,
            message: '请选择支付方式-译文',
            trigger: ['blur']
        }
    ],
    shop_text_payin: [
        {
            required: true,
            message: '跳转支付中-译文',
            trigger: ['blur']
        }
    ],
    shop_text_payfail: [
        {
            required: true,
            message: '支付失败-译文',
            trigger: ['blur']
        }
    ],
    shop_text_paysuccess: [
        {
            required: true,
            message: '支付成功-译文',
            trigger: ['blur']
        }
    ],
    shop_text_paynot: [
        {
            required: true,
            message: '没有数据-译文',
            trigger: ['blur']
        }
    ],
    name: [
        {
            required: true,
            message: '请输入网站名称',
            trigger: ['blur']
        }
    ],
    web_favicon: [
        {
            required: true,
            message: '请选择网站图标',
            trigger: ['change']
        }
    ],
    web_logo: [
        {
            required: true,
            message: '请选择网站logo',
            trigger: ['change']
        }
    ],
    login_image: [
        {
            required: true,
            message: '请选择登录页广告图',
            trigger: ['change']
        }
    ],
    shop_name: [
        {
            required: true,
            message: '请输入店铺/商城名称',
            trigger: ['blur']
        }
    ],
    shop_url: [
        {
            required: true,
            message: '请输入前台域名',
            trigger: ['blur']
        }
    ],
    shop_logo: [
        {
            required: true,
            message: '请选择商城LOGO',
            trigger: ['change']
        }
    ],
    pc_logo: [
        {
            required: true,
            message: '请选择PC端LOGO',
            trigger: ['change']
        }
    ],
    pc_title: [
        {
            required: true,
            message: '请输入PC端网站标题',
            trigger: ['blur']
        }
    ],
    pc_ico: [
        {
            required: true,
            message: '请选择PC端网站图标',
            trigger: ['change']
        }
    ]
}

// 获取备案信息
const getData = async () => {
    const data = await getWebsite()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

// 设置备案信息
const handleSubmit = async () => {
    await formRef.value?.validate()
    await setWebsite(formData)
    appStore.getConfig()
    getData()
}

getData()
</script>

<style lang="scss" scoped></style>
