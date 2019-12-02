<template>
    <div class="modify-phone-box">
      <ModifyHeader title="修改手机号"></ModifyHeader>
      <main class="modify-phone-main content">
        <div class="modify-phone-form my-info-form">
          <van-cell-group>

            <van-field
              v-if="modifyState"
              v-model="phoneNum"
              clearable
              label="验证旧手机"
              placeholder="请输入旧手机号"
              readonly
            />

            <van-field
              v-else
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
              <van-button slot="button" size="small" type="default" @click="sendSmsCodePhone()">发送验证码</van-button>
            </van-field>

          </van-cell-group>
        </div>

        <div class="modify-phone-operating">
          <van-button type="primary" v-if="modifyState" @click="nextStep" >下一步</van-button>
          <van-button type="primary" v-else @click="bindNewPhone()">提交</van-button>
        </div>

      </main>
    </div>
</template>

<script>
import '../../../scss/m_site/myInfo/myInfo.scss';
import '../../../less/m_site/myInfo/myInfo.less';
import modifyPhoneCon from '../../../controllers/m_site/myInfo/modifyPhoneCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "modify-phone-view",
  ...modifyPhoneCon
}
</script>
