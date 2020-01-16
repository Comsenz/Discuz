<template>
  <section>
    <van-popup
      class="sidebarWrap"
      v-model="popupShow"
      position="right"
      :style="{'height':'100%','right': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"
   >
    <sidebar :isPayVal = "isPayVal"></sidebar>
    </van-popup>
    <!-- 侧边栏 -E -->
    <div class="headerBox" v-if="$route.meta.oneHeader">
      <div class="invitePerDet aaa" v-show="invitePerDet">
        <!-- <div class="invitePerDet" v-show="invitePerDet"> -->
        <!-- <img src="../../../../../../static/images/noavatar.gif" class="inviteHead"> -->
          <img v-if="userInfoAvatarUrl" :src="userInfoAvatarUrl" alt="" class="inviteHead">
          <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" alt="ssss" class="inviteHead">
          <div class="inviteName" v-if="invitePerDet && userInfoName" v-model="userInfoName">{{userInfoName}}</div>
          <div class="inviteName" v-else="">该用户已被删除</div>
          <p class="inviteWo" v-show="invitationShow">邀请您加入</p>
      </div>
      <div class="headeGap" v-if="!searchIconShow && !menuIconShow"></div>
      <div class="headOpe" v-if="searchIconShow || menuIconShow">
        <!-- <span class="icon iconfont icon-search" v-show="backIconShow"></span> -->
        <span class="icon iconfont icon-search" @click="searchJump" v-show="searchIconShow"></span>
        <span class="icon iconfont icon-Shape" is-link @click="showPopup" v-show="menuIconShow"></span>
      </div>
      <div class="logoBox" v-show="logoShow">
        <img v-if="logo" :src="logo" class="logo">
        <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="logo">
      </div>


      <div class="circleDet" v-show="perDetShow" v-if="siteInfo">
        <span>主题：{{siteInfo._data.threads}}</span>
        <span>成员：{{siteInfo._data.members}}</span>
        <span v-if="siteInfo._data.siteAuthor">站长：{{siteInfo._data.siteAuthor.username}}</span>
        <span v-else="">站长：无</span>
      </div>
      <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-show="navShow">
        <van-tabs v-model="navActi" >
          <van-tab v-for="(cateChi, index) in categories" :key="index">
            <span slot="title" v-on:click="categoriesCho(cateChi._data.id)">
                {{cateChi._data.name}}
            </span>
          </van-tab>
        </van-tabs>
      </div>
    </div>
  </section>
</template>
<script>
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Sidebar from '../../m_site/common/sidebarView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "headerView",
  components:{
  	Sidebar
  },
  ...mSiteHeader
}

</script>
