<template>
    <div class="wx-login-bd-box">
      <LoginHeader></LoginHeader>
      <main class="wx-login-bd-main">
        <div class="wx-login-bd-title-box">
          <h2 class="wx-login-bd-title-h2">登录绑定帐号</h2>
          <div class="wx-login-main-desc">登录并绑定微信帐号</div>
        </div>

        <form class="wx-login-bd-form">
          <van-cell-group>
            <van-field
              clearable
              v-model="userName"
              label="用户名"
              placeholder="请输入用户名"
            />

            <van-field
              clearable
              type="password"
              v-model="password"
              label="密码"
              placeholder="请填写密码"
            />
          </van-cell-group>
        </form>

        <div class="wx-login-bd-btn">
          <van-button plain type="primary" :loading="btnLoading" @click="loginBdClick">登录并绑定</van-button>
        </div>

      </main>
      <LoginFooter></LoginFooter>
    </div>
</template>

<script>
import '../../../defaultLess/m_site/modules/loginSignUpModule.less'
import wxLoginBdCon from '../../../controllers/m_site/login/wxLoginBdCon';
export default {
    name: "wx-login-bd",
  ...wxLoginBdCon
}
</script>
