<template>
    <div class="">
      <walletDetailsHeader title="钱包明细"></walletDetailsHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check="false"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <main class="content">
        <Panenl :title="item.title"
                :status="item.status"
                :num="item._data.change_available_amount"
                v-for="(item,index) in walletDetailsList"
                :key="index">
          <!-- <span slot="label">当前可用金额：{{item.attributes.change_freeze_amount}}</span> -->
          <span slot="label">{{$dayjs(item._data.created_at).format('YYYY-MM-DD HH:mm')}}</span>
        </Panenl>
      </main>
      <footer class="my-info-money-footer"></footer>
</van-pull-refresh>
  </van-list>
    </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
// import  '../../../scss/m_site/mobileIndex.scss';

import '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import walletDetailsCon from '../../../../controllers/m_site/myInfo/myWallet/walletDetailsCon';
export default {
    name: "wallet-details-view",
  ...walletDetailsCon
}
</script>
