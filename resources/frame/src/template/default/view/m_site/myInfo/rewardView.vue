<template>
  <div class="reward-box my-info-money-header">
    <RewardHeader title="打赏我的"></RewardHeader>
    <main class="reward-main">
      <div class="reward-con cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <ContMain
          :contText="contText">
        </ContMain>
      </div>
      <div class="reward-con cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <ContMain
          :contText="contText">
        </ContMain>
      </div>
    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import rewardCon from '../../../controllers/m_site/myInfo/rewardCon'
export default {
  name: "reward",
  ...rewardCon
}
</script>
