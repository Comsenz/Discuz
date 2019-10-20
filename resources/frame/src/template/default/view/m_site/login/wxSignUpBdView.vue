<template>
  <div class="wx-sign-up-bd-box">
    <LoginHeader></LoginHeader>
    <main class="weui-form wx-sign-up-bd-main">
      <div class="weui-form__text-area wx-sign-up-bd-title">
        <h2 class="weui-form__title">微信绑定账号</h2>
        <div class="weui-form__desc">您的微信号未绑定账号，请登录绑定您的账号</div>
      </div>

      <div class="weui-form__control-area">
        <div class="weui-cells__group weui-cells__group_form">
          <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
              <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
              <div class="weui-cell__bd">
                <input id="js_input" class="weui-input" placeholder="请输入您的用户名">
              </div>
            </div>
            <div class="weui-cell">
              <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
              <div class="weui-cell__bd">
                <input id="js_input" class="weui-input" placeholder="请填写密码">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="wx-sign-up-bd-btn">
        <button class="weui-btn weui-btn_primary "  id="showTooltips">注册并绑定</button>
      </div>

    </main>
    <LoginFooter></LoginFooter>
  </div>
</template>

<script>
import '../../../scss/mobile/login/wxSignUpBdView.scss';
import wxLoginBdCon from '../../../controllers/m_site/login/wxSignUpBdCon';
export default {
    name: "wx-sign-up-bd",
  ...wxLoginBdCon
}
</script>
