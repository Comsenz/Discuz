<template>
    <div class="login-user-box">
      <LoginHeader></LoginHeader>
      <main class="login-user-box-main">
        <div class="login-user-title-box">
          <p class="login-user-title-p">用户名登录</p>
        </div>

        <form class="weui-cells__group weui-cells__group_form user-login-form">
          <div class="weui-cells weui-cells_form login-user-input-form">
            <div class="weui-cell">
              <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
              <div class="weui-cell__bd">
                <input id="js_input" class="weui-input" placeholder="请输入您的用户名">
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

        <div class="login-user-btn">
          <button class="weui-btn weui-btn_primary login-btn">登录</button>
        </div>

        <div class="login-user-method">
          <div class="login-user-method-box weui-loadmore weui-loadmore_line">
            <span class="weui-loadmore__tips">其他登录方式</span>
          </div>

          <div class="login-user-method-icon">
            <div class="login-user-method-icon-box">

              <i @click="loginPhoneClick" class="login-user-method-icon-iphone iconfont">
                <span class="icon iconfont" style="color:rgba(136, 136, 136, 1);">&#xe60d;</span>
              </i>

              <i class="login-user-method-icon-iphone iconfont">
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
import '../../../scss/mobile/login/loginUserView.scss';
import loginCon from '../../../controllers/m_site/login/loginUserCon'
export default {
    name: "login-view",
  ...loginCon
}
</script>

