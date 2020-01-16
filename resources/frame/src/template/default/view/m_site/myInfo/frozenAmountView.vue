<template>
    <div class="frozen-amount-box my-info-money-header">
      <FrozenAmountHeader title="冻结金额"></FrozenAmountHeader>
      <main  class="frozen-amount-main content">
        <Panenl :title="item.attributes.change_freeze_amount+item.attributes.change_desc" :num="item.attributes.change_freeze_amount" v-for="(item,index) in walletFrozenList" :key="index">
          <span slot="label">{{$moment(item.attributes.created_at).format('YYYY-MM-DD HH:mm')}}</span>
        </Panenl>
      </main>
      <footer class="frozen-amount-footer my-info-money-footer"></footer>
    </div>
</template>

<script>
import frozenAmountCon from '../../../controllers/m_site/myInfo/frozenAmountCon';
import '../../../less/m_site/myInfo/myInfo.less'
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "frozen-amount-view",
  ...frozenAmountCon
}
</script>
