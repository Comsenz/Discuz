<template>
  <section>
    <van-popup
      class="sidebarWrap"
      v-model="popupShow"
      position="right"
      :style="{'height':'100%','right': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"
    >
      <sidebar :isPayVal="isPayVal"></sidebar>
    </van-popup>
    <!-- 侧边栏 -E -->
    <div class="headerBox" v-if="$route.meta.oneHeader">
      <div class="invitePerDet" v-show="invitePerDet">
        <div v-if="userInfoAvatarUrl" class="inviteHead">
          <img :src="userInfoAvatarUrl" alt="用户头像" class="user-img" />
          <img
            class="icon-yirenzheng"
            src="../../../../../../static/images/authIcon.svg"
            alt="实名认证"
          />
        </div>

        <img v-else :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" alt class="inviteHead" />
        <div class="inviteName" v-if="invitePerDet && userInfoName" v-model="userInfoName">
          {{userInfoName}}
          <p class="groupsName" v-if="showGroups && showGroups.status">({{showGroups.name}})</p>
        </div>
        <!-- <div class="inviteName" v-else="">该用户已被删除</div> -->
        <p class="inviteWo" v-show="invitationShow">邀请您加入</p>
        <div class="followBox" v-if="followShow && followDet">
          <span>关注：{{followDet._data.followCount}}</span>
          <span>被关注：{{followDet._data.fansCount}}</span>
          <div href="javascript:;" v-if="!equalId" class="followStatus">
            <a
              href="javascript:;"
              class
              v-if="intiFollowVal == '0'"
              @click="followCli(intiFollowVal)"
            >关注TA</a>
            <a
              href="javascript:;"
              class
              v-else-if="intiFollowVal == '2'"
              @click="followCli(intiFollowVal)"
            >相互关注</a>
            <a
              href="javascript:;"
              class
              v-else-if="intiFollowVal == '1'"
              @click="followCli(intiFollowVal)"
            >已关注</a>
            <!-- <a href="javascript:;" class="" v-else="" style="display: none;"></a> -->
          </div>
        </div>
      </div>
      <div class="headeGap" v-if="!searchIconShow && !menuIconShow && !followShow"></div>
      <div class="headOpe" v-if="searchIconShow || menuIconShow">
        <!-- <span class="icon iconfont icon-search" v-show="backIconShow"></span> -->
        <span class="icon iconfont icon-search" @click="searchJump" v-show="searchIconShow"></span>
        <span
          class="icon iconfont icon-Shape relative"
          is-link
          @click="showPopup"
          v-show="menuIconShow"
        >
          <i class="noticeNew" v-if="noticeSum>0"></i>
        </span>
      </div>
      <div class="logoBox" v-show="logoShow">
        <img v-if="logo" :src="logo" class="logo" />
      </div>

      <div class="circleDet" v-show="perDetShow" v-if="siteInfo">
        <span>主题：{{siteInfo._data.other.count_threads}}</span>
        <span>帖子：{{siteInfo._data.other.count_posts}}</span>
        <span v-if="siteInfo._data.other.count_users">成员：{{siteInfo._data.other.count_users}}</span>
        <span v-else>成员：无</span>
      </div>
      <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-show="navShow">
        <van-tabs v-model="navActi">
          <van-tab>
            <span slot="title" class="title-span" v-on:click="categoriesCho(0)">全部</span>
          </van-tab>
          <van-tab v-for="(cateChi, index) in categories" :key="index">
            <span
              slot="title"
              class="title-span"
              v-on:click="categoriesCho(cateChi._data.id)"
            >{{cateChi._data.name}}</span>
          </van-tab>
        </van-tabs>
      </div>
    </div>
  </section>
</template>
<script>
import mSiteHeader from "../../../controllers/m_site/common/headerCon";
import Sidebar from "../../m_site/common/sidebarView";
// import '../../../scss/m_site/mobileIndex.scss';
import "../../../defaultLess/m_site/common/common.less";
export default {
  name: "headerView",
  components: {
    Sidebar
  },
  ...mSiteHeader
};
</script>
