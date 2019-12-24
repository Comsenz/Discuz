<template>
  <footer class="login-user-footer">
    <template v-if="pageName === 'login-user'||pageName === 'login-phone'">
      <span v-if="qcloudSms" @click="retrieveClick">忘记密码？找回</span>
      <i v-if="registerClose && qcloudSms"></i>
      <span v-if="registerClose" @click="signUpClick">注册</span>
    </template>

    <template v-else-if="pageName === 'wx-login-bd'||pageName === 'wx-sign-up-bd'">
      <span @click="wxSignUpBdClick">没有账号？注册，绑定微信新账号</span>
    </template>

    <template v-else-if="pageName === 'sign-up'">
      <span @click="loginClick">已有账号立即登录</span>
    </template>

    <template v-else-if="pageName === 'bind-phone'">
      <span @click="homeClick">{{siteMode === 'pay'?'跳过，进入支付费用':'跳过，进入首页'}}</span>
    </template>

    <template v-else-if="pageName === 'retrieve-pwd'">
      <span></span>
    </template>

    <template v-else>
      <span></span>
    </template>

  </footer>
</template>

<script>
import '../../../../scss/m_site/common/loginSignUpFooter/loginSignUpFooter.scss'
import loginSignUpFooterCon from '../../../../controllers/m_site/common/loginSignUpFooter/loginSignUpFooter'
export default {
    name: "login-sign-up-footer",
  ...loginSignUpFooterCon
}
</script>

