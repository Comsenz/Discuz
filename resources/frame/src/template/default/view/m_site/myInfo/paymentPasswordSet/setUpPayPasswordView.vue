<template>
  <div class="pay-password-box">
    <setUpPayPwdHeader title="设置钱包密码"></setUpPayPwdHeader>

    <div class="pay-password-box_title">
      <h1>设置钱包密码</h1>
      <p>请设置钱包密码，用于钱包支付验证</p>
    </div>

    <van-password-input
      class="passwordInp"
      :value="value"
      :focused="showKeyboard"
      @focus="showKeyboard = true"
    />

    <van-number-keyboard
      safe-area-inset-bottom
      :show="showKeyboard"
      @input="onInput"
      @delete="onDelete"
      @blur="showKeyboard = false"
    />


  </div>
</template>

<script>
  import '../../../../defaultLess/m_site/modules/myInfo.less';
  import setUpPayPasswordCon from '../../../../controllers/m_site/myInfo/paymentPasswordSet/setUpPayPasswordCon';
  export default {
    name: "setUpPayPasswordView",
    ...setUpPayPasswordCon
  }
</script>
