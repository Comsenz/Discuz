<template>
    <div class="pay-password-box">
      <confirmPayPwdHeader title="设置钱包密码"></confirmPayPwdHeader>

      <div class="pay-password-box_title">
        <h1>设置钱包密码</h1>
        <p>请再次填写以确认</p>
      </div>

      <van-password-input
        class="passwordInp"
        :value="value"
        :focused="showKeyboard"
        @focus="showKeyboard = true"
        :error-info="errorInfo"
      />

      <van-number-keyboard
        safe-area-inset-bottom
        :show="showKeyboard"
        @input="onInput"
        @delete="onDelete"
        @blur="showKeyboard = false"
      />

      <van-button type="primary" :loading="subLoading" loading-text="提交中" @click="submitClick">完成</van-button>

    </div>
</template>

<script>
  import '../../../../defaultLess/m_site/modules/myInfo.less';
  import confirmPayPasswordCon from '../../../../controllers/m_site/myInfo/paymentPasswordSet/confirmPayPasswordCon';
  export default {
    name: "confirmPayPasswordView",
    ...confirmPayPasswordCon
  }
</script>
