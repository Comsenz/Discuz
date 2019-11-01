<template>
  <div class="my-info-money-header">
    <orderDetailsHeader title="订单明细"></orderDetailsHeader>
    <main>
      <Panenl title="打赏帖子“国庆快乐”" num="-1000.00">
        <span slot="label">支付成功</span>
        <span slot="label">流水号：34927384234</span>
        <span slot="label">2019-08-10 20:30</span>
      </Panenl>
      <Panenl title="加入圈子“天涯杂谈”" num="-1000.00">
        <span slot="label">支付成功</span>
        <span slot="label">流水号：34927384234</span>
        <span slot="label">2019-08-10 20:30</span>
      </Panenl>
    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import orderDetailsCon from '../../../controllers/m_site/myInfo/orderDetailsCon'
export default {
    name: "order-details-view",
  ...orderDetailsCon
}
</script>
