<template>
  <div class="bgEd">
    <myWalletHeader title="我的钱包"></myWalletHeader>
    <div class="content">
      <main class="like-main">
        <van-cell title="可用余额" is-link :value="value+'元'"/>
        <van-cell title="冻结金额" @click="myWallet('frozen-amount')" is-link :value="valueFrozen+'元'"/>
        <van-cell title="提现记录" @click="myWallet('withdrawals-record')" is-link />
        <van-cell title="可用余额" is-link />
      </main>
      <footer class="my-info-money-footer"></footer>
    </div>

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
