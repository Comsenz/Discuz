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
              clearable
              type="password"
              label="密码"
              placeholder="请填写密码"
              v-model="password"
              :error="error"
              :error-message="errorMessage"
              @clear="clearError('clear')"
              @focus="clearError('focus')"
              @input="clearError('input')"
              @blur="clearError('blur')"
            />
          </van-cell-group>
        </form>

        <div class="sign-up-btn">
          <van-button @click="signUpClick" :loading="btnLoading"  type="primary">注册</van-button>
        </div>

      </main>

      <SignUpFooter></SignUpFooter>
    </div>
</template>
<style>
  .toast {            position: fixed;            z-index: 2000;            left: 50%;            top:45%;            transition:all .5s;            -webkit-transform: translateX(-50%) translateY(-50%);               -moz-transform: translateX(-50%) translateY(-50%);                -ms-transform: translateX(-50%) translateY(-50%);                 -o-transform: translateX(-50%) translateY(-50%);                    transform: translateX(-50%) translateY(-50%);            text-align: center;            border-radius: 5px;            color:#FFF;            background: rgba(17, 17, 17, 0.7);            height: 45px;            line-height: 45px;            padding: 0 15px;            max-width: 150px;        }
</style>
<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import signUpCon from '../../../controllers/m_site/login/signUpCon';
export default {
    name: "sign-up-view",
  ...signUpCon
}
</script>
