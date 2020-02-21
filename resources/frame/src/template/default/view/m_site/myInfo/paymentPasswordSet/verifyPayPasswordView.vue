<template>
    <div class="pay-password-box">
      <verifyPayPwdHeader title="设置支付密码"></verifyPayPwdHeader>

      <div class="pay-password-box_title">
        <h1>验证身份</h1>
        <p>请输入支付密码，以验证身份</p>
      </div>

      <van-password-input
        class="passwordInp"
        :value="value"
        :focused="showKeyboard"
        @focus="showKeyboard = true"
      />
      <p class="forGetPwd" @click="$router.push({path:'retrieve-pwd',query:{type:'forget'}})">忘记密码</p>

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
  import verifyPayPasswordCon from '../../../../controllers/m_site/myInfo/paymentPasswordSet/verifyPayPasswordCon'
  export default {
    name: "verifyPayPasswordView",
    ...verifyPayPasswordCon
  }
</script>
