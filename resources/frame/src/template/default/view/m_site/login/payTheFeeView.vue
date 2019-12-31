<template>
    <div class="pay-the-fee-box">
      <PayHeader></PayHeader>

      <main class="pay-the-fee-main">
        <div class="pay-the-fee-titel">
          <h2>支付费用</h2>
        </div>
        <van-button type="primary" @click="payClick">立即付费,获得权限</van-button>
        <p class="pay-the-fee-title-footer">￥{{sitePrice}} / {{siteExpire}}</p>
      </main>

      <div class="pay-the-fee-permission">
        <div class="pay-the-fee-per-title">
          <h3>作为成员，您将获得以下权限</h3>
        </div>
        <div class="pay-the-fee-per-list">
          <div class="pay-the-fee-per-name">帖子操作</div>
          <p>查看主题</p>
          <p>发图文贴</p>
        </div>
        <div class="pay-the-fee-per-list-footer">
          <p @click="leapFrogClick">跳过，进入首页</p>
        </div>
      </div>

      <van-popup
        v-model="qrcodeShow"
        round
        close-icon-position="top-right"
        closeable
        class="qrCodeBox"
        get-container="body">
        <span class="popupTit">立即支付</span>
        <div class="payNum">￥<span>{{amountNum}}</span></div>
        <div class="payType">
          <span class="typeLeft">支付方式</span>
          <span class="typeRight"><i class="icon iconfont icon-wepay"></i>微信支付</span>
        </div>
        <img :src="codeUrl" alt="微信支付二维码" class="qrCode">
        <p class="payTip">微信识别二维码支付</p>
      </van-popup>

      <van-dialog
        v-model="dialogShow"
        title="请确认微信支付是否完成"
        :show-confirm-button="false"
        :show-cancel-button="false"
      >
        <van-button type="primary" block @click="completePayment">已完成支付</van-button>
        <van-button type="primary" block>支付遇到问题，重新支付</van-button>
      </van-dialog>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import payTheFeeCon from '../../../controllers/m_site/login/payTheFeeCon';
export default {
    name: "pay-the-fee-view",
  ...payTheFeeCon
}
</script>

