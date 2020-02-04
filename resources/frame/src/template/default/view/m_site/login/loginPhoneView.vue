<template>
    <div  class="login-phone-box">
      <LoginHeader></LoginHeader>
      <main class="login-phone-box-main">
        <div class="login-module-title-box">
          <p class="login-module-title">手机号登录</p>
        </div>

        <div class="login-module-form">
          <van-cell-group>
            <van-field
              label="手机号"
              v-model="phone"
              placeholder="请输入您的手机号"
            />

            <van-field
              v-model="sms"
              center
              clearable
              label="验证码"
              placeholder="请输入验证码"
            >
              <van-button slot="button" size="small" :disabled="disabled" :class="{'grayBg':isGray}" type="default" @click="getCode">{{btnContent}}</van-button>
            </van-field>

          </van-cell-group>
        </div>

        <div class="login-phone-btn">
          <van-button type="primary" @click="phoneLoginClick">登录</van-button>
        </div>

        <div class="login-phone-method">
          <div class="login-phone-method-box ">
            <van-divider>其他登录方式</van-divider>
          </div>

          <div class="login-phone-method-icon">
            <div class="login-phone-method-icon-box" :class="{'justifyCenter':isOne}">
              <i @click="loginUserClick" class="login-phone-method-icon-ring iconfont">
                <span class="icon iconfont icon-yonghu" style="color:rgba(136, 136, 136, 1);"></span>
              </i>
              <i @click="wxLoginClick" class="login-phone-method-icon-ring iconfont" v-show="wxLoginShow">
                <span class="icon iconfont icon-weixin" style="color:rgba(136, 136, 136, 1);"></span>
              </i>
            </div>
          </div>
        </div>
      </main>
      <LoginFooter></LoginFooter>
    </div>
</template>

<script>
import '../../../defaultLess/m_site/modules/loginSignUpModule.less'
import '../../../defaultLess/m_site/common/common.less'
import loginPhoneCon from '../../../controllers/m_site/login/loginPhoneCon';
export default {
    name: "loging-phone-view",
  ...loginPhoneCon
}
</script>
