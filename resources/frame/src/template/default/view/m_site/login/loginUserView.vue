<template>
    <div class="login-user-box">
      <LoginHeader></LoginHeader>
      <main class="login-user-box-main">
        <div class="login-user-title-box login-module-title-box">
          <p class="login-user-title-p login-module-title">用户名登录</p>
        </div>

        <form class="user-login-form login-module-form">
          <van-cell-group>
            <van-field
              v-model="userName"
              clearable
              label="用户名"
              placeholder="请输入您的用户名"
            />

            <van-field
              v-model="password"
              type="password"
              clearable
              label="密码"
              placeholder="请填写密码"
            />
          </van-cell-group>
        </form>


        <div class="login-user-btn">
          <van-button type="primary" @click="loginClick" :loading="btnLoading" loading-text="登录中..." >登录</van-button>
        </div>

        <div class="login-user-method">
          <div class="login-user-method-box">
            <van-divider v-show="phoneStatus ||  wxLoginShow">其他登录方式</van-divider>
          </div>

          <div class="login-user-method-icon">
            <div class="login-user-method-icon-box" :class="{'justifyCenter':isOne}" >
              <i @click="loginPhoneClick" class="login-user-method-icon-ring iconfont" v-if="phoneStatus">
                <span class="icon iconfont icon-shouji" style="color:rgba(136, 136, 136, 1);"></span>
              </i>

              <i @click="loginWxClick" class="login-user-method-icon-ring iconfont" v-show="wxLoginShow">
                <span class="icon iconfont icon-weixin" style="color:rgba(136, 136, 136, 1);"></span>
              </i>

            </div>
          </div>
        </div>
      </main>
      <LoginFooter></LoginFooter>
    </div>
</template>

<script>
import '../../../../../../static/css/iconfont.css';
import '../../../scss/m_site/login/loginSignUpModule.scss';
import loginCon from '../../../controllers/m_site/login/loginUserCon';
export default {
    name: "login-view",
  ...loginCon
}
</script>
