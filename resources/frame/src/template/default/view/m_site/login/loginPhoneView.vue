<template>
    <div class="login-phone-box">
      <LoginHeader></LoginHeader>
      <main class="login-phone-box-main">
        <div class="login-phone-title-box">
          <p class="login-phone-title-p">手机号登录</p>
        </div>

        <form class="weui-cells__group weui-cells__group_form phone-login-form">
          <div class="weui-cells weui-cells_form login-phone-input-form">
            <div class="weui-cell">
              <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
              <div class="weui-cell__bd">
                <input id="js_input" class="weui-input" placeholder="请输入您的手机号">
              </div>
            </div>
            <div class="weui-cell">
              <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
              <div class="weui-cell__bd">
                <input id="js_input" class="weui-input" type="password" placeholder="请填写密码">
              </div>
            </div>
          </div>
        </form>

        <div class="login-phone-btn">
          <button class="weui-btn weui-btn_primary login-btn">登录</button>
        </div>

        <div class="login-phone-method">
          <div class="login-phone-method-box weui-loadmore weui-loadmore_line">
            <span class="weui-loadmore__tips">其他登录方式</span>
          </div>

          <div class="login-phone-method-icon">
            <div class="login-phone-method-icon-box">

              <i @click="loginUserClick" class="login-phone-method-icon-iphone iconfont">
                <span class="icon iconfont" style="color:rgba(136, 136, 136, 1);">&#xe60f;</span>
              </i>

              <i class="login-phone-method-icon-iphone iconfont">
                <span class="icon iconfont" style="color:rgba(136, 136, 136, 1);">&#xe60e;</span>
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
import '../../../scss/mobile/login/loginPhoneView.scss';
import loginPhoneCon from '../../../controllers/m_site/login/loginPhoneCon';
export default {
    name: "loging-phone-view",
  ...loginPhoneCon
}
</script>
