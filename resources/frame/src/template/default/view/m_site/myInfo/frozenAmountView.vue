<template>
    <div class="frozen-amount-box">
      <FrozenAmountHeader title="冻结金额"></FrozenAmountHeader>
      <main  class="frozen-amount-main">
        <Panenl title="1000元提现中" num="-1000.00">
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="2000元提现中" num="-2000.00">
          <span slot="label">流水号：22222</span>
          <span slot="label">2019-08-11 21:30</span>
        </Panenl>
        <Panenl title="2000元提现中" num="-2000.00">
          <span slot="label">支付成功</span>
          <span slot="label">流水号：22222</span>
          <span slot="label">2019-08-11 21:30</span>
        </Panenl>
      </main>
    </div>
</template>

<script>
import frozenAmountCon from '../../../controllers/m_site/myInfo/frozenAmountCon';
// import '../../../less/m_site/myInfo'
export default {
    name: "frozen-amount-view",
  ...frozenAmountCon
}
</script>
