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
              <i class="login-user-method-icon-iphone">
                <svg t="1571402781821" class="icon icon-iphone" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3934" width="40" height="30"><path d="M736 48H288a48 48 0 0 0-48 48v832a48 48 0 0 0 48 48h448a48 48 0 0 0 48-48V96a48 48 0 0 0-48-48zM512 928a32 32 0 1 1-0.016-63.984A32 32 0 0 1 512 928z m224-112H288V144h448v672z" fill="" p-id="3935"></path></svg>
              </i>
              <i class="login-user-method-icon-iphone">
                <svg t="1571402781821" class="icon icon-iphone" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3934" width="40" height="30"><path d="M736 48H288a48 48 0 0 0-48 48v832a48 48 0 0 0 48 48h448a48 48 0 0 0 48-48V96a48 48 0 0 0-48-48zM512 928a32 32 0 1 1-0.016-63.984A32 32 0 0 1 512 928z m224-112H288V144h448v672z" fill="" p-id="3935"></path></svg>
              </i>
            </div>


            <!--<i></i>-->
          </div>
        </div>
      </main>
      <LoginFooter></LoginFooter>
    </div>
</template>

<script>
import '../../../scss/mobile/login/loginIndex.scss';
import loginCon from '../../../controllers/m_site/login/loginCon'
export default {
    name: "login-view",
  ...loginCon
}
</script>

