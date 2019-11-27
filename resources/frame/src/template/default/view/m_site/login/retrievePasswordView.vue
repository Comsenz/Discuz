<template>
    <div class="retrieve-password-box">
      <retrievePWDHeader></retrievePWDHeader>
      <main class="retrieve-password-main">

        <div class="login-module-title-box">
          <h2 class="login-module-title">忘记密码</h2>
        </div>

        <!--<div class="weui-form__control-area">
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
        </div>-->

        <div class="login-module-form">
          <van-cell-group>
            <van-field
              label="手机号"
              v-model="phoneNum"
              placeholder="请输入您的手机号"
            />

            <van-field
              v-model="verifyNum"
              center
              clearable
              label="验证码"
              placeholder="请输入验证码"
            >
              <van-button slot="button" size="small" type="default" @click="forgetSendSmsCode" :class="{'grayBg':isGray}">{{ btnContent }}</van-button>
            </van-field>

            <van-field
              label="新密码"
              v-model="newpwd"
              placeholder="请输入新密码"
            />

          </van-cell-group>
        </div>


        <div class="retrieve-password-btn">
          <van-button type="primary" @click="submissionPassword">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import  '../../../scss/m_site/mobileIndex.scss';
import retrievePasswordCon from '../../../controllers/m_site/login/retrievePasswordCon';
export default {
    name: "retrieve-password-view",
  ...retrievePasswordCon
}
</script>
