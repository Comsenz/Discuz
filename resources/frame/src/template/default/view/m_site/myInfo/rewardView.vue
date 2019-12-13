<template>
  <div class="reply-my-box my-info-money-header">
    <comHeader title="打赏我的"></comHeader>
    <div class="content">
        <Header :userInfoList="rewardList" :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false"  :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="rewardList"></ThemeDet>
    </div>
    <footer class="home-page-footer">
      <p>上划加载更多</p>
    </footer>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>
    <!-- <RewardHeader title="打赏我的"></RewardHeader> -->
    <!-- <main class="reward-main content">
      <div class="reward-con cell-crossing" v-for="(item,index) in rewardList" :key="index">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <ContMain
          :contText="contText">
        </ContMain>
      </div> -->
      <!-- <div class="reward-con cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <ContMain
          :contText="contText">
        </ContMain>
      </div> -->
    <!-- </main> -->

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import rewardCon from '../../../controllers/m_site/myInfo/rewardCon';
import Header from '../../m_site/common/headerView';
import ThemeDet from '../../m_site/common/themeDetView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "reward",
   components:{
      comHeader,
    	Header,
      ThemeDet,
    },
  ...rewardCon,
  // ...mSiteThemeDet,
}
</script>
