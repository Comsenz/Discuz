<template>
  <div class="reply-my-box my-info-money-header">
    <comHeader title="回复我的"></comHeader>
    <div class="content">
        <Header :userInfoList="replyList" :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false" :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="replyList"></ThemeDet>
    </div>
    <footer class="home-page-footer">
      <p>上划加载更多</p>
    </footer>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import replyCon from '../../../controllers/m_site/myInfo/replyCon';
import  '../../../scss/m_site/mobileIndex.scss';
import ThemeDet from '../../m_site/common/themeDetView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
export default {
  name: "reply",
  components:{
      comHeader,
    	// Header,
      ThemeDet,
    },
    ...mSiteThemeDet,
  ...replyCon
}
</script>
