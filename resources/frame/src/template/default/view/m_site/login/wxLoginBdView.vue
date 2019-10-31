<template>
    <div class="wx-login-bd-box">
      <LoginHeader></LoginHeader>
      <main class="wx-login-bd-main">
        <div class="wx-login-bd-title-box">
          <h2 class="wx-login-bd-title-h2">微信绑定账号</h2>
          <div class="wx-login-main-desc">您的微信号未绑定账号，请登录绑定您的账号</div>
        </div>

        <!--<form class="weui-form__control-area">
          <div class="weui-cells__group weui-cells__group_form">
            <div class="weui-cells weui-cells_form">
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
                <div class="weui-cell__bd">
                  <input id="js_input" class="weui-input" placeholder="请输入您的用户名">
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
                <div class="weui-cell__bd">
                  <input id="js_input" class="weui-input" placeholder="请填写密码">
                </div>
              </div>
            </div>
          </div>
        </form>-->


        <form class="wx-login-bd-form">
          <van-cell-group>
            <van-field
              clearable
              label="用户名"
              placeholder="请输入您的用户名"
            />

            <van-field
              type="password"
              label="密码"
              placeholder="请填写密码"
            />
          </van-cell-group>
        </form>

        <div class="wx-login-bd-btn">
          <van-button type="primary">登录并绑定</van-button>
        </div>

      </main>
      <LoginFooter></LoginFooter>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import wxLoginBdCon from '../../../controllers/m_site/login/wxLoginBdCon';
export default {
    name: "wx-login-bd",
  ...wxLoginBdCon
}
</script>
