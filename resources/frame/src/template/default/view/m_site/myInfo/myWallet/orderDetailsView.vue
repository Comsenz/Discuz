<template>
  <div class="my-info-money-header">
    <orderDetailsHeader title="订单明细"></orderDetailsHeader>
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
      <Panel :title="`${type[item._data.type]} “${item._data.title}”`" :num="'-'+item._data.amount" v-for="(item,index) in orderList" :key="index"  @click="jumpDetails(item.thread._data.id)">
        <span slot="label"  >{{status[item._data.status]}}</span>
        <span slot="label"  >流水号 : {{item._data.order_sn}}</span>
        <span slot="label"  >{{$moment(item._data.created_at).format('YYYY-MM-DD HH:mm')}}</span>
      </Panel>
    </main>
    </van-pull-refresh>
    </van-list>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import orderDetailsCon from '../../../../controllers/m_site/myInfo/myWallet/orderDetailsCon';
export default {
    name: "order-details-view",
  ...orderDetailsCon
}
</script>
