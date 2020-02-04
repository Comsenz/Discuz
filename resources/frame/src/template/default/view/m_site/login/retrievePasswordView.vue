<template>
    <div class="retrieve-password-box">
      <retrievePWDHeader></retrievePWDHeader>
      <main class="retrieve-password-main">

        <div class="login-module-title-box">
          <h2 class="login-module-title">忘记密码</h2>
        </div>

        <div class="login-module-form">
          <van-cell-group>
            <van-field
              label="手机号"
              v-model="phoneNum"
              placeholder="请输入您的手机号"
            />

            <van-field
              v-model="verifyNum"
              center
              clearable
              label="验证码"
              placeholder="请输入验证码"
            >
              <van-button slot="button" size="small" type="default" @click="forgetSendSmsCode" :class="{'grayBg':isGray}">{{ btnContent }}</van-button>
            </van-field>

            <van-field
              label="新密码"
              v-model="newpwd"
              placeholder="请输入新密码"
            />

          </van-cell-group>
        </div>


        <div class="retrieve-password-btn">
          <van-button type="primary" @click="submissionPassword">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../defaultLess/m_site/modules/loginSignUpModule.less'
import '../../../defaultLess/m_site/common/common.less'
import retrievePasswordCon from '../../../controllers/m_site/login/retrievePasswordCon';
export default {
    name: "retrieve-password-view",
  ...retrievePasswordCon
}
</script>
