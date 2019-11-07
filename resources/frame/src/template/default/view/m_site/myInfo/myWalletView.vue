<template>
  <div class="bgEd">
    <myWalletHeader title="我的钱包"></myWalletHeader>
    <main class="like-main">
      <van-cell title="可用余额" is-link is-link value="6423.00元"/>
      <van-cell title="冻结金额" is-link is-link value="3054.23元"/>
      <van-cell title="提现记录" is-link />
      <van-cell title="可用余额" is-link />
    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
import  '../../../scss/m_site/mobileIndex.scss';
import myWalletCon from '../../../controllers/m_site/myInfo/myWalletCon';
export default {
  name: "myWallet-view",
  ...myWalletCon
}
</script>
