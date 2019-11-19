<template>
    <div class="withdrawals-record-box my-info-money-header">
      <WithdrawHeader title="提现记录"></WithdrawHeader>
      <main class="withdrawals-record-main content">
        <Panenl title="待审核" num="-1000.00">
          <span slot="label">流水号：34927384234</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="审核不通过，原因：使用非正常手段获取利益" num="-1000.00">
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
        </Panenl>
      </main>
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
