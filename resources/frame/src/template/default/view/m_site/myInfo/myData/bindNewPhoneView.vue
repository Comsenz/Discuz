<template>
  <div class="modify-phone-box">
    <ModifyHeader v-if="headerShow" :title="titlePhone"></ModifyHeader>
    <main class="modify-phone-main content">
      <div class="modify-phone-form my-info-form">
        <van-cell-group>
          <van-field
            v-model="newphone"
            clearable
            label="设置新手机"
            maxlength="13"
            placeholder="请输入新手机号"
          />
          <van-field v-model="sms" clearable label="验证码" placeholder="请输入验证码">
            <van-button
              slot="button"
              size="small"
              :disabled="disabled"
              type="default"
              @click="sendSmsCodePhone"
            >{{btnContent}}</van-button>
          </van-field>
        </van-cell-group>
      </div>

      <div class="modify-phone-operating">
        <van-button type="primary" :loading="btnLoading" loading-text="提交中" @click="bindNewPhone">提交</van-button>
      </div>

      <!--      <div class="loadFix" v-if="loading">-->
      <!--        <div class="loadMask"></div>-->
      <!--        <van-loading color="#333333" class="loadIcon" type="spinner" />-->
      <!--      </div>-->
    </main>
  </div>
</template>

<script>
import "../../../../defaultLess/m_site/modules/myInfo.less";
import "../../../../defaultLess/m_site/common/common.less";
import modifyPhoneCon from "../../../../controllers/m_site/myInfo/myData/bindNewPhoneCon.js";
export default {
  name: "modify-phone-view",
  ...modifyPhoneCon
};
</script>
