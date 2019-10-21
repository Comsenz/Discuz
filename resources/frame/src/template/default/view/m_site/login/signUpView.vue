<template>
    <div class="sign-up-box">
      <SignUpHeader></SignUpHeader>

      <main class="sign-up-main">
        <div class="weui-form__text-area sign-up-main-header">
          <h2 class="weui-form__title">注册</h2>
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

        <div class=" sign-up-btn">
          <button class="weui-btn weui-btn_primary">注册</button>
        </div>

      </main>

      <SignUpFooter></SignUpFooter>
    </div>
</template>

<script>
import '../../../scss/mobile/login/signUpView.scss';
import signUpCon from '../../../controllers/m_site/login/signUpCon';
export default {
    name: "sign-up-view",
  ...signUpCon
}
</script>
