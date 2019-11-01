<template>
    <div class="sign-up-box">
      <SignUpHeader></SignUpHeader>

      <main class="sign-up-main">
        <div class="login-module-title-box">
          <p class="login-module-title">注册</p>
        </div>

        <!--<div class="weui-form__control-area">
          <div class="weui-cells__group weui-cells__group_form">
            <div class="weui-cells weui-cells_form">
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
                <div class="weui-cell__bd">
                  <input v-model="username" id="js_input" class="weui-input" placeholder="请输入您的用户名">
                </div>
              </div>

              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
                <div class="weui-cell__bd">
                  <input v-model="password" id="js_input" type="password" class="weui-input" placeholder="请填写密码">
                </div>
              </div>
            </div>
          </div>
        </div>-->

        <form class="login-module-form">
          <van-cell-group>
            <van-field
              clearable
              label="用户名"
              placeholder="请输入您的用户名"
              v-model="username"
            />

            <van-field
              type="password"
              label="密码"
              placeholder="请填写密码"
              v-model="password"
            />
          </van-cell-group>
        </form>

        <div class="sign-up-btn">
          <van-button @click="signUpClick" type="primary">注册</van-button>
        </div>

      </main>

      <SignUpFooter></SignUpFooter>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import signUpCon from '../../../controllers/m_site/login/signUpCon';
export default {
    name: "sign-up-view",
  ...signUpCon
}
</script>
