<template>
    <div class="withdrawals-record-box my-info-money-header">
      <WithdrawHeader title="提现记录"></WithdrawHeader>
      <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check='immediateCheck'
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <main class="withdrawals-record-main content" >
        <Panenl :title='cashStatusObj[item.attributes.cash_status]' :num='item.attributes.cash_apply_amount' v-for="(item,index) in withdrawalsList" :key="index"  v-if="withdrawalsList.length>0">
          <span slot="label">流水号：{{item.attributes.cash_sn}}</span>
          <span slot="label">{{$moment(item.attributes.created_at).format('YYYY-MM-DD HH:mm')}}</span>
        </Panenl>
        <!-- <Panenl title="审核不通过，原因：使用非正常手段获取利益" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核通过，打款中" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl> -->
      </main>
</van-pull-refresh>    
  </van-list>
      <footer class="my-info-money-footer"></footer>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import withdrawCon from '../../../controllers/m_site/myInfo/withdrawalsRecordCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "withdraw-view",
  ...withdrawCon
}
</script>
