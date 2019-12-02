<template>
  <section>
    <van-popup
      class="sidebarWrap"
      v-model="popupShow"
      position="right"
      :style="{ height: '100%' }"
   >
      <sidebar></sidebar>
    </van-popup>
    <!-- 侧边栏 -E -->
    <div class="headerBox" v-if="$route.meta.oneHeader">
      <div class="invitePerDet" v-show="invitePerDet">
        <!-- <img src="../../../../../../static/images/noavatar.gif" class="inviteHead"> -->
        <img v-if="userInfoAvatarUrl" :src="userInfoAvatarUrl" alt="aaaa" class="inviteHead" v-model="userInfoAvataUrl">
        <img v-else="" src="../../../../../../static/images/noavatar.gif" alt="ssss" class="inviteHead">
        <div class="inviteName" v-model="userInfoName">{{userInfoName}}</div>
        <p class="inviteWo" v-show="invitationShow">邀请您加入</p>
      </div>
      <div class="headOpe">
        <!-- <span class="icon iconfont icon-search" v-show="backIconShow"></span> -->
        <span class="icon iconfont icon-search" v-show="searchIconShow"></span>
        <span class="icon iconfont icon-Shape" is-link @click="showPopup" v-show="menuIconShow"></span>
      </div>
      <img src="../../../../../../static/images/logo.png" class="logo" v-show="logoShow">
      <div class="circleDet" v-model="perDet" v-show="perDetShow">
        <span v-model="perDet.themeNum">主题：{{perDet.themeNum}}</span>
        <span v-model="perDet.memberNum">成员：{{perDet.memberNum}}</span>
        <span v-model="perDet.circleLeader">圈主：{{perDet.circleLeader}}</span>
      </div>
      <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-show="navShow">
        <van-tabs v-model="navActi">
          <van-tab v-for="(navChi, index) in themeNavList" :title="navChi.text" :key="index">
          </van-tab>
        </van-tabs>
        <!-- <div class="navBarBox">
          <ul class="navBarCon">
            <li v-for="(navChi, index) in themeNavList" v-on:click="addClass(index,$event)" v-bind:class="{ navActi:index==current}">{{ navChi.text }}</li>
          </ul>
        </div> -->
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
