<template>
  <div class="change-pwd-box">
    <ChangePWDHeader title="修改密码"></ChangePWDHeader>
    <main class="change-pwd-main">
      <div class="change-pwd-form my-info-form">
        <van-cell-group>
          <van-field label="旧密码" v-model="pwd" type="password" placeholder="请输入您的旧密码" />
          <van-field label="新密码" v-model="newpwd" type="password" placeholder="请输入您的新密码" />
          <van-field label="确认密码" v-model="confirmpwd" type="password" placeholder="请输入您的确认密码" />
        </van-cell-group>
      </div>

      <div class="change-pwd-operating">
        <van-button :loading="btnLoading" loading-text="提交中" type="primary" @click="subm">提交</van-button>
      </div>

    </main>
  </div>
</template>

<script>
import "../../../../defaultLess/m_site/modules/myInfo.less";
import changePwdCon from "../../../../controllers/m_site/myInfo/myData/changePwdCon";
export default {
  name: "change-password-view",
  ...changePwdCon
};
</script>
