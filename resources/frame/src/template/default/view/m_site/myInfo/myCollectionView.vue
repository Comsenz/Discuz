<template>
  <!-- <div class="my-collection-box">
    <CollectionHeader title="我的收藏"></CollectionHeader>
    <div class="content">
        <Header :userInfoList="collectionList" :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false" :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="collectionList"></ThemeDet>
      </div>
      <footer class="home-page-footer">
        <p>上划加载更多</p>
      </footer> -->
    <!-- <main class="my-collection-main content">
      <div class="my-collection-main-cont" v-for="(item,index) in collectionList" :key="index">

        <div class="home-page-cont"> -->
          <!-- <ContHeader
            :imgUrl="imgUrl"
            :stateTitle="stateTitle"
            :time="item.attributes.createdAt"
            :user="item.user().id()"
            :username222="item.user().username()"
            :userName="userName">
          </ContHeader> -->
          <!-- <ContMain
            :contText="item.contText">
          </ContMain>
          <ContFooter></ContFooter>
        </div>
      </div> -->
      <!-- <div class="my-collection-main-cont">
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
    <!-- </main> -->
  <!-- </div> -->
   <div class="reply-my-box">
    <comHeader title="我的收藏"></comHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check="false"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <div class="content" >
        <ThemeDet :themeList="collectionList" :isMoreShow="false"></ThemeDet>
      </div>
    </van-pull-refresh>
  </van-list>

    <!-- <footer class="my-info-money-footer"></footer> -->
  </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
import Header from '../../m_site/common/headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import ThemeDet from '../../m_site/common/themeDetView';
import myCollectionCon from '../../../controllers/m_site/myInfo/myCollectionCon';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
// import '../../../scss/m_site/mobileIndex.scss';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
import '../../../defaultLess/m_site/modules/myInfo.less';
export default {
  name: "my-collection-view",
   components:{
      comHeader,
      Header,
      ThemeDet
    },
  ...myCollectionCon,
  mSiteThemeDet
}
</script>
