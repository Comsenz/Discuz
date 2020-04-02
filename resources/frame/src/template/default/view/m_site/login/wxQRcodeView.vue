<template>
    <div class="wx-qr-code-box">
      <h2>微信登录</h2>
      <div class="img-box">
        <img class="qrcode" :src="wxUrl" alt="微信扫描二维码">
        <div class="wx-qr-info">
          <div class="wx-qr-info__title">
            <p>请使用微信扫描二维码登录</p>
            <p>“{{siteName}}”</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import '../../../defaultLess/m_site/modules/loginSignUpModule.less';
import '../../../defaultLess/m_site/common/common.less';
import wxQRcodeCon from "../../../controllers/m_site/login/wxQRcodeCon";
export default {
  name: "wxQRcode",
  ...wxQRcodeCon
}
</script>

