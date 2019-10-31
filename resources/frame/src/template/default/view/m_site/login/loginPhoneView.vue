<template>
    <div class="login-phone-box">
      <LoginHeader></LoginHeader>
      <main class="login-phone-box-main">
        <div class="login-module-title-box">
          <p class="login-module-title">手机号登录</p>
        </div>


        <!--<form class="weui-cells__group weui-cells__group_form phone-login-form">
          <div class="weui-cells weui-cells_form login-phone-input-form">

            <div class="weui-cell">
              <div class="weui-cell__hd">
                <label class="weui-label">手机号</label>
              </div>
              <div class="weui-cell__bd">
                <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入手机号" >
              </div>
            </div>

            <div class="weui-cell weui-cell_vcode">
              <div class="weui-cell__hd">
                <label class="weui-label">验证码</label>
              </div>
              <div class="weui-cell__bd">
                <input autofocus="" class="weui-input" type="text" pattern="[0-9]*" id="js_input" placeholder="输入验证码" maxlength="6">
              </div>
              <div class="weui-cell__ft">
                <a href="javascript:;" @click="getYZ" class="weui-btn weui-btn_default weui-vcode-btn verification-code-btn">获取验证码</a>
              </div>
            </div>

          </div>
        </form>-->

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
              <van-button slot="button" size="small" type="default">发送验证码</van-button>
            </van-field>

          </van-cell-group>
        </div>


        <div class="login-phone-btn">
          <van-button type="primary">登录</van-button>
        </div>

        <div class="login-phone-method">
          <div class="login-phone-method-box weui-loadmore weui-loadmore_line">
            <span class="weui-loadmore__tips">其他登录方式</span>
          </div>

          <div class="login-phone-method-icon">
            <div class="login-phone-method-icon-box">

              <i @click="loginUserClick" class="login-phone-method-icon-ring iconfont">
                <span class="icon iconfont icon-yonghu" style="color:rgba(136, 136, 136, 1);"></span>
              </i>

              <i @click="wxLoginClick" class="login-phone-method-icon-ring iconfont">
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
import '../../../../../../static/css/iconfont.css';
import '../../../scss/m_site/login/loginSignUpModule.scss';
import loginPhoneCon from '../../../controllers/m_site/login/loginPhoneCon';
export default {
    name: "loging-phone-view",
  ...loginPhoneCon
}
</script>
