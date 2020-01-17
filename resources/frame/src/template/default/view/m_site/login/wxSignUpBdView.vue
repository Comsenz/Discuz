<template>
  <div class="wx-sign-up-bd-box">
    <LoginHeader></LoginHeader>
    <main class="wx-sign-up-bd-main">
      <div class="wx-sign-up-bd-title-box">
        <h2 class="wx-sign-up-bd-title-box-h2">微信绑定账号</h2>
        <div class="wx-sign-up-main-desc">您的微信号未绑定账号，请注册并绑定您的账号</div>
      </div>

      <form class="wx-sign-up-bd-form" action="">
        <van-cell-group>
          <van-field
            clearable
            v-model="userName"
            label="用户名"
            placeholder="请输入您的用户名"
          />

          <van-field
            type="password"
            v-model="password"
            label="密码"
            placeholder="请填写密码"
          />
        </van-cell-group>
      </form>

      <div class="wx-sign-up-bd-btn">
        <van-button type="primary" @click="signUpBdClick">注册并绑定</van-button>
      </div>

    </main>
    <LoginFooter></LoginFooter>
  </div>
</template>

<script>
// import '../../../scss/m_site/login/loginSignUpModule.scss';

import '../../../defaultLess/m_site/modules/loginSignUpModule.less'
import wxLoginBdCon from '../../../controllers/m_site/login/wxSignUpBdCon';
export default {
    name: "wx-sign-up-bd",
  ...wxLoginBdCon
}
</script>
