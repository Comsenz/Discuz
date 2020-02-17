<template>
    <div class="verify-pay-password-box">
      <verifyPayPwdHeader></verifyPayPwdHeader>

      <div class="verify-pay-password-box_title">
        <h1>验证身份</h1>
        <p class="">请输入支付密码，以验证身份</p>
      </div>

      <van-password-input
        class="passwordInp"
        :value="value"
        info="密码为 6 位数字"
        :focused="showKeyboard"
        @focus="showKeyboard = true"
      />

      <van-number-keyboard

        :show="showKeyboard"
        @input="onInput"
        @delete="onDelete"
        @blur="showKeyboard = false"
      />

    </div>
</template>
<style>
  .passwordInp:after {
    border-radius: 0;
    border-left-width: 1px;
    border-right-width: 1px;
    border-top-width: 1px !important;
  }
  .passwordInp li.van-hairline--left:after {
    border-left-width: 1px;
  }
</style>
<script>
  import '../../../../defaultLess/m_site/modules/myInfo.less';
  import verifyPayPasswordCon from '../../../../controllers/m_site/myInfo/paymentPasswordSet/verifyPayPasswordCon'
  export default {
    name: "verifyPayPasswordView",
    ...verifyPayPasswordCon
  }
</script>
