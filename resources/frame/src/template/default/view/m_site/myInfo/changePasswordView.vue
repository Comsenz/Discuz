<template>
    <div class="change-pwd-box">
      <ChangePWDHeader title="修改密码"></ChangePWDHeader>
      <main class="change-pwd-main">
        <div class="change-pwd-form my-info-form">
          <van-cell-group>
            <van-field
              label="旧密码"
              v-model="pwd"
              placeholder="请输入您的旧密码"
            />
            <van-field
              label="新密码"
              v-model="newpwd"
              placeholder="请输入您的新密码"
            />
            <van-field
              label="确认密码"
              v-model="confirmpwd"
              placeholder="请输入您的确认密码"
            />
          </van-cell-group>
        </div>

        <div class="change-pwd-operating">
          <van-button type="primary" >提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import changePwdCon from '../../../controllers/m_site/myInfo/changePwdCon';
import  '../../../scss/m_site/myInfo/myInfo.scss';
export default {
    name: "change-password-view",
  ...changePwdCon
}
</script>
