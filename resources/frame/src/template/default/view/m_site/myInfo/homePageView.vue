<template>
  <div class="home-page-box" v-if="followDet" v-model="followDet">
    <comHeader v-if="followDet._data.username" :title="followDet._data.username+'的个人主页'"></comHeader>
    <van-list
      v-model="loading"
      :finished="finished"
      :offset="offset"
      finished-text="没有更多了"
      @load="onLoad"
      :immediate-check="false"
    >
      <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
        <div class="content">
          <!-- <Header
            :showGroups="userGroups"
            :userInfoAvatarUrl="userAvatar"
            :followShow="true"
            :userInfoName="username"
            :navShow="false"
            :invitePerDet="true"
            :headFixed="false"
            :logoShow="false"
            :searchIconShow="false"
            :menuIconShow="false"
            :perDetShow="false"
          ></Header>-->
          <div class="personHead">
            <div class="perHeadCon">
              <div class="perHeadImgBox">
                <img :src="followDet._data.avatarUrl" alt="用户头像" class="perHeadImg" />
                <img
                  class="icon-yirenzheng"
                  v-if="followDet._data.isReal"
                  src="../../../../../../static/images/authIcon.svg"
                  alt="实名认证"
                />
              </div>
              <div class="userDetInfo">
                <h1 class="userDetName">{{followDet._data.username}}</h1>
                <span class="userDetRole">{{followDet.groups[0]._data.name}}</span>
              </div>
              <div class="userOpera">
                <!-- <a href="javascript:;" class="perLetter">私信</a> -->
                <a
                  href="javascript:;"
                  class="followStatus"
                  v-if="intiFollowVal == '0'"
                  @click="followCli(intiFollowVal)"
                >关注TA</a>
                <a
                  href="javascript:;"
                  class="followStatus"
                  v-else-if="intiFollowVal == '2'"
                  @click="followCli(intiFollowVal)"
                >相互关注</a>
                <a
                  href="javascript:;"
                  class="followStatus"
                  v-else-if="intiFollowVal == '1'"
                  @click="followCli(intiFollowVal)"
                >已关注</a>
              </div>
            </div>
          </div>
          <div class="personDetails">
            <div class="personDetChi">
              <h2 class="personDetNum">{{followDet._data.threadCount}}</h2>
              <div class="personDetType">主题</div>
            </div>
            <div class="personDetChi">
              <h2 class="personDetNum">{{followDet._data.followCount}}</h2>
              <div class="personDetType">关注</div>
            </div>
            <div class="personDetChi">
              <h2 class="personDetNum">{{followDet._data.fansCount}}</h2>
              <div class="personDetType">粉丝</div>
            </div>
            <!-- <div class="personDetChi">
              <h2 class="personDetNum">{{followDet._data.followCount}}</h2>
              <div class="personDetType">点赞</div>
            </div>-->
          </div>
          <div class="gap"></div>
          <ThemeDet :themeList="OthersThemeList"></ThemeDet>
        </div>
      </van-pull-refresh>
    </van-list>
    <footer class="home-page-footer" v-if="OthersThemeList != null && OthersThemeList != ''">
      <!-- <p>上划加载更多</p> -->
    </footer>
  </div>
</template>

<script>
import mSiteHeader from "../../../controllers/m_site/common/headerCon";
import Header from "../../m_site/common/headerView";
import homePageCon from "../../../controllers/m_site/myInfo/homePageCon";
import comHeader from "../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader";
import ThemeDet from "../../m_site/common/themeDetView";
import mSiteThemeDet from "../../../controllers/m_site/common/themeDetCon";
import "../../../defaultLess/m_site/common/common.less";
import "../../../defaultLess/m_site/modules/circle.less";
import "../../../defaultLess/m_site/modules/myInfo.less";
export default {
  name: "home-page-view",
  components: {
    comHeader,
    Header,
    ThemeDet
  },
  // ...mSiteHeader,
  ...mSiteThemeDet,
  ...homePageCon
};
</script>
