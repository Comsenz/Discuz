<template>
    <div class="my-info-money-header">
      <walletDetailsHeader title="钱包明细"></walletDetailsHeader>
      <main class="content">
        <Panenl :title="type[item.attributes.change_type]" :num="item.attributes.change_freeze_amount" v-for="(item,index) in walletDetailsList" :key="index">
          <span slot="label">当前可用金额：{{item.attributes.change_freeze_amount}}</span>
          <span slot="label">{{$moment(item.attributes.created_at).format('YYY-MM-DD HH:mm')}}</span>
        </Panenl>
        <!-- <Panenl title="小虫加入圈子“国庆快乐“，圈主超超收益100元" num="0.00">
          <span slot="label">当前可用金额：0.00</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="小虫加入圈子“电子设备“，圈主杨鹏收益10000元" num="+100000000.00">
          <span slot="label">当前可用金额：0.00</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl>
        <Panenl title="小虫加入圈子“生活乐趣“，圈主奶罩收益1000元" num="+1000.00">
          <span slot="label">当前可用金额：0.00</span>
          <span slot="label">2019-08-10 20:30</span>
        </Panenl> -->
      </main>
      <footer class="my-info-money-footer"></footer>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import walletDetailsCon from '../../../controllers/m_site/myInfo/walletDetailsCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "wallet-details-view",
  ...walletDetailsCon
}
</script>
