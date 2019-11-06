<template>
    <div class="home-page-box">
      <HomePageHeader title="Eliza的个人主页"></HomePageHeader>
      <main class="home-page-main">

        <div class="home-page-main-header">
          <div class="home-page-main-box">
            <img class="header-avatar avatar-img" src="../../../../../../static/images/mytx.png" alt="用户头像">
            <p class="user-name">Elizabeth</p>
          </div>
        </div>

        <div class="home-page-main-cont">
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
        </div>

        <div class="home-page-main-cont">
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
        </div>


      </main>
      <footer class="home-page-footer">
        <p>上划加载更多</p>
      </footer>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import homePageCon from '../../../controllers/m_site/myInfo/homePageCon';
export default {
    name: "home-page-view",
  ...homePageCon
}
</script>
