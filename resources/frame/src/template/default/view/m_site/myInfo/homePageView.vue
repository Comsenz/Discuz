<template>
    <div class="home-page-box">
      <comHeader title="Eliza的个人主页"></comHeader>
      <div class="content">
        <Header :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false" :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="OthersThemeList"></ThemeDet>
        <!-- <div class="home-page-main-header">
          <div class="home-page-main-box">
            <img class="header-avatar avatar-img" src="../../../../../../static/images/mytx.png" alt="用户头像">
            <p class="user-name">Elizabeth</p>
          </div>
        </div> -->

        <!-- <div class="home-page-main-cont">
          <div class="barrier"></div>
          <div class="home-page-cont">
            <ContHeader
              :imgUrl="imgUrl"
              :stateTitle="stateTitle"
              :time="time"
              :userName="userName">
            </ContHeader>

            <ContMain
              :contText="contText">
            </ContMain>

            <ContFooter></ContFooter>

          </div>
        </div> -->

        <!-- <div class="home-page-main-cont">
          <div class="barrier"></div>

          <div class="home-page-cont">
            <ContHeader
              :imgUrl="imgUrl"
              :stateTitle="stateTitle"
              :time="time"
              :userName="userName">
            </ContHeader>
            <ContMain
              :contText="contText">
            </ContMain>
            <ContFooter></ContFooter>

          </div>
        </div> -->


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
