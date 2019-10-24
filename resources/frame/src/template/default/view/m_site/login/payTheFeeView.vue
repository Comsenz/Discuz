<template>
    <div class="pay-the-fee-box">
      <PayHeader></PayHeader>
      <main class="pay-the-fee-main">
        <div class="pay-the-fee-titel">
          <h2>支付费用</h2>
        </div>
        <button class="pay-the-fee-btn weui-btn weui-btn_primary">立即付费,获得权限</button>
        <p class="pay-the-fee-title-footer">￥20.00 / 永久有效</p>
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
    </div>
</template>

<script>
import '../../../scss/mobile/login/loginSignUpModule.scss';
import payTheFeeCon from '../../../controllers/m_site/login/payTheFeeCon';
export default {
    name: "pay-the-fee-view",
  ...payTheFeeCon
}
</script>

