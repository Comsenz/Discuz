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
          <div class="followBox" v-if="followShow">
            <span>关注：{{followDet._data.followCount}}</span>
            <span>被关注：{{followDet._data.fansCount}}</span>
            <a v-if="userId != personUserId" href="javascript:;" id="followCli" class="followOne" @click="followCli(intiFollowVal)">{{followFlag}}</a>
            <!-- <a v-if="userId != personUserId" href="javascript:;" id="followCli" class="followOne">关注TA</a> -->
          </div>
      </div>
      <div class="headeGap" v-if="!searchIconShow && !menuIconShow"></div>
      <div class="headOpe" v-if="searchIconShow || menuIconShow">
        <!-- <span class="icon iconfont icon-search" v-show="backIconShow"></span> -->
        <span class="icon iconfont icon-search" @click="searchJump" v-show="searchIconShow"></span>
        <span class="icon iconfont icon-Shape relative" is-link @click="showPopup" v-show="menuIconShow"><i class="noticeNew" v-if="noticeSum>0"></i></span>
      </div>
      <div class="logoBox" v-show="logoShow">
        <img v-if="logo" :src="logo" class="logo">
        <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="logo">
      </div>


      <div class="circleDet" v-show="perDetShow" v-if="siteInfo">
        <span>主题：{{siteInfo._data.other.count_threads}}</span>
        <span>成员：{{siteInfo._data.other.count_users}}</span>
        <span v-if="siteInfo._data.set_site.site_author">站长：{{siteInfo._data.set_site.site_author.username}}</span>
        <span v-else="">站长：无</span>
      </div>
      <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-show="navShow">
        <van-tabs v-model="navActi">
          <van-tab>
            <span slot="title" v-on:click="categoriesCho(0)">
                全部
            </span>
          </van-tab>
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
// import '../../../scss/m_site/mobileIndex.scss';
import  '../../../defaultLess/m_site/common/common.less';
export default {
  name: "headerView",
  components:{
  	Sidebar
  },
  ...mSiteHeader
}

</script>
