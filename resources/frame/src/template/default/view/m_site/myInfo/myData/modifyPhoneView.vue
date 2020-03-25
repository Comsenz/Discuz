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

          <van-field v-else v-model="newphone" clearable label="设置新手机" placeholder="请输入新手机号" />

          <van-field v-model="sms" clearable label="验证码" placeholder="请输入验证码">
            <van-button
              slot="button"
              size="small"
              type="default"
              @click="sendSmsCodePhone"
            >{{ btnContent }}</van-button>
          </van-field>
        </van-cell-group>
      </div>

      <div class="modify-phone-operating">
        <van-button :loading="loading" loading-text="验证中" type="primary" v-if="modifyState" @click="nextStep">下一步</van-button>
        <van-button :loading="loading" loading-text="提交中" type="primary" v-else @click="bindNewPhone">提交</van-button>
      </div>

      <div class="loadFix" v-if="loading">
        <div class="loadMask"></div>
        <van-loading color="#333333" class="loadIcon" type="spinner" />
      </div>
    </main>
  </div>
</template>

<script>
import "../../../../defaultLess/m_site/modules/myInfo.less";
import "../../../../defaultLess/m_site/common/common.less";
import modifyPhoneCon from "../../../../controllers/m_site/myInfo/myData/modifyPhoneCon";
export default {
  name: "modify-phone-view",
  ...modifyPhoneCon
};
</script>
