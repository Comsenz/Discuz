<template>
  <div class="my-info-money-header">
    <orderDetailsHeader title="订单明细"></orderDetailsHeader>
    <main class="content">
      <Panenl :title="type[item.attributes.type]" :num="item.attributes.amount" v-for="(item,index) in orderList" :key="index">
        <span slot="label">{{status[item.attributes.status]}}</span>
        <span slot="label">流水号 : {{item.attributes.order_sn}}</span>
        <span slot="label">{{$moment(item.attributes.created_at).format('YYYY-MM-DD HH:mm')}}</span>
      </Panenl>
      <!-- <Panenl title="加入站点“天涯杂谈”" num="-1000.00">
        <span slot="label">支付成功</span>
        <span slot="label">流水号：34927384234</span>
        <span slot="label">2019-08-10 20:30</span>
      </Panenl> -->
    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import orderDetailsCon from '../../../controllers/m_site/myInfo/orderDetailsCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "order-details-view",
  ...orderDetailsCon
}
</script>
