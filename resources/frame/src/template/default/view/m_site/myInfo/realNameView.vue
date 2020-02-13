<template>
    <div class="change-pwd-box">
      <ChangePWDHeader title="实名认证"></ChangePWDHeader>
      <main class="change-pwd-main">
        <div class="change-pwd-form my-info-form">
          <van-cell-group>
            <van-field
              label="姓名"
              v-model="name"
             
              placeholder="请输入您的真实姓名"
            />
            <van-field
              label="身份证号"
              v-model="idNumber"
              type="password"
              placeholder="请输入您的身份证号码"
            />
          </van-cell-group>
        </div>

        <div class="change-pwd-operating">
          <van-button type="primary" @click="subm()">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../defaultLess/m_site/modules/myInfo.less';
import realNameCon from '../../../controllers/m_site/myInfo/realNameCon.js';
export default {
    name: "real-name-view",
  ...realNameCon
}
</script>