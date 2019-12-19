<template>
    <div class="sign-up-box">
      <SignUpHeader></SignUpHeader>

      <main class="sign-up-main">
        <div class="login-module-title-box">
          <p class="login-module-title">注册</p>
        </div>

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

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import signUpCon from '../../../controllers/m_site/login/signUpCon';

export default {
    name: "sign-up-view",
  ...signUpCon
}
</script>
