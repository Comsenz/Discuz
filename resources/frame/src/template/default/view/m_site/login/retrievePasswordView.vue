<template>
    <div class="retrieve-password-box">
      <retrievePWDHeader></retrievePWDHeader>
      <main class="retrieve-password-main">

        <div class="weui-form__text-area retrieve-password-main-header">
          <h2 class="weui-form__title">忘记密码</h2>
        </div>

        <div class="weui-form__control-area">
          <div class="weui-cells__group weui-cells__group_form">
            <div class="weui-cells weui-cells_form">
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
                <div class="weui-cell__bd">
                  <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入您的手机号">
                </div>
              </div>
              <div class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
                <div class="weui-cell__bd">
                  <input autofocus="" class="weui-input" type="text" pattern="[0-9]*" id="js_input" placeholder="请输入验证码" maxlength="6">
                </div>
                <div class="weui-cell__ft">
                  <button class="weui-btn weui-btn_default weui-vcode-btn verification-code-btn">获取验证码</button>
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">新密码</label></div>
                <div class="weui-cell__bd">
                  <input id="js_input" class="weui-input" placeholder="请输入新密码">
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="retrieve-password-btn">
          <button class="weui-btn weui-btn_primary login-btn">提交</button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import retrievePasswordCon from '../../../controllers/m_site/login/retrievePasswordCon';
export default {
    name: "retrieve-password-view",
  ...retrievePasswordCon
}
</script>
