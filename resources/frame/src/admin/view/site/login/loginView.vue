<template>
    <div class="login-box">
      <main class="login-main">
        <header class="login-header">
          <a href="/">
            <img src="../../../../../static/images/logo.png" alt="LOGO">
          </a>
        </header>

        <div class="login-main-form">
          <el-form ref="form" :model="form" :rules="rules"  label-width="0px">
            <el-form-item prop="user">
              <el-input
                placeholder="请输入用户名"
                clearable
                v-model="form.user">
                <span slot="prefix" class="iconfont iconuser"></span>
              </el-input>
            </el-form-item>
            <el-form-item prop="password">
              <el-input
                placeholder="请输入密码"
                type="password"
                show-password
                clearable
                @keyup.enter.native="adminLogin('form')"
                v-model="form.password">
                <span slot="prefix" class="iconfont iconpassword"></span>
              </el-input>
            </el-form-item>

            <el-form-item class="login-main-form__item">
              <!--<el-checkbox  v-model="checked">记住密码</el-checkbox>-->
            </el-form-item>

            <el-form-item class="login-main-form__item">
              <el-button type="primary" @click="adminLogin('form')">登录</el-button>
            </el-form-item>

          </el-form>

        </div>

      </main>

      <footer class="login-footer">
        <p>Powered by Discuz! Q</p>
      </footer>
      <!--<p>{{this.$store.state.login.loginState}}</p>-->
    </div>
</template>

<script>
import loginCon from '../../../controllers/site/login/loginCon';
import '../../../scss/site/module/loginStyle.scss';
import '../../../../../static/css/admin/iconfont.css'
export default {
  name: "login-view",
  ...loginCon
}
</script>
