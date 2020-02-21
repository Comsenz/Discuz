<template>
  <div class="bgEd">
    <myWalletHeader title="我的钱包"></myWalletHeader>
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    <div class="content">
      <main class="like-main">
        <van-cell title="可用余额" @click="myWallet('withdraw')" is-link :value="value+'元'"/>
        <van-cell title="冻结金额" @click="myWallet('frozen-amount')" is-link :value="valueFrozen+'元'"/>
        <van-cell title="提现记录" @click="myWallet('withdrawals-record')" is-link />
        <van-cell title="钱包明细" @click="myWallet('wallet-details')" is-link />
        <van-cell title="订单明细" @click="myWallet('order-details')" is-link />
      </main>
      <footer class="my-info-money-footer"></footer>
    </div>
    </van-pull-refresh>
  </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
// import  '../../../scss/m_site/mobileIndex.scss';

import '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import myWalletCon from '../../../../controllers/m_site/myInfo/myWallet/myWalletCon';
export default {
  name: "myWallet-view",
  ...myWalletCon
}
</script>
