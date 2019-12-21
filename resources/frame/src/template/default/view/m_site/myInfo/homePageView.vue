<template>
    <div class="home-page-box">
      <comHeader v-if="username" :title="username+'的个人主页'"></comHeader>
      <!-- <comHeader v-else="" title="该用户已被删除的个人主页"></comHeader> -->
      <div class="content">
        <Header :userInfoAvataUrl="userAvatar" :userInfoName="username" :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false" :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="OthersThemeList"></ThemeDet>
      </div>
      <footer class="home-page-footer">
        <p>上划加载更多</p>
      </footer>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import homePageCon from '../../../controllers/m_site/myInfo/homePageCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import ThemeDet from '../../m_site/common/themeDetView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
// import homePageCon from '../../../controllers/m_site/myInfo/homePageCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "home-page-view",
    components:{
      comHeader,
    	Header,
      ThemeDet
    },
    // ...mSiteHeader,
    ...mSiteThemeDet,
    ...homePageCon
}
</script>
