<template>
    <div class="modify-phone-box">
      <ModifyHeader title="修改手机号"></ModifyHeader>
      <main class="modify-phone-main content">
        <div class="modify-phone-form my-info-form">
          <van-cell-group>
            <van-field
              v-model="newphone"
              clearable
              label="设置新手机"
              placeholder="请输入新手机号"
            />

            <van-field
              v-model="sms"
              clearable
              label="验证码"
              placeholder="请输入验证码"
            >
              <van-button slot="button" size="small" type="default" @click="sendSmsCodePhone">{{btnContent}}</van-button>
              
            </van-field>

          </van-cell-group>
        </div>

        <div class="modify-phone-operating">
          <van-button type="primary" @click="bindNewPhone">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../scss/m_site/myInfo/myInfo.scss';
import '../../../less/m_site/myInfo/myInfo.less';
import modifyPhoneCon from '../../../controllers/m_site/myInfo/bindNewPhoneCon.js';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "modify-phone-view",
  ...modifyPhoneCon
}
</script>