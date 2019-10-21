<template>
  <footer class="login-user-footer">
    <template v-if="pageName === 'login_user'||pageName === 'login_phone'">
      <span @click="retrieveClick">忘记密码？找回</span>
      <i></i>
      <span @click="signUpClick">注册</span>
    </template>

    <template v-else-if="pageName === 'wx_login_bd'||pageName === 'wx_sign_up_bd'">
      <span @click="wxSignUpBdClick">没有账号？注册，绑定微信新账号</span>
    </template>

    <template v-else-if="pageName === 'sign_up'">
      <span @click="loginClick">已有账号立即登录</span>
    </template>

    <template v-else-if="pageName === 'bind_phone'">
      <span @click="homeClick">跳过，进入首页</span>
    </template>

    <template v-else-if="pageName === 'retrieve_pwd'">
      <span></span>
    </template>

    <template v-else>
      <span></span>
    </template>

  </footer>
</template>

<script>
import '../../../../scss/mobile/common/loginSignUpFooter/loginSignUpFooter.scss'
import loginSignUpFooterCon from '../../../../controllers/m_site/common/loginSignUpFooter/loginSignUpFooter'
export default {
    name: "login-sign-up-footer",
  ...loginSignUpFooterCon
}
</script>

