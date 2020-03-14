<!--移动端首页模板-->
<template>
  <div class="circleCon">
    <van-list
      v-model="loading"
      :finished="finished"
      :offset="offset"
      :finished-text="pageIndex === 1 && themeListCon.length === 0 ? '暂无数据':'没有更多了'"
      @load="onLoad"
      :immediate-check="false"
    >
      <van-pull-refresh v-model="isLoading" @refresh="onRefresh" style="overflow:visible">
        <Header
          :searchIconShow="false"
          :perDetShow="true"
          :logoShow="true"
          :menuIconShow="false"
          :navShow="true"
          :invitePerDet="true"
          :headFixed="false"
          @categoriesChoice="categoriesChoice"
          v-on:update="receive"
          :userInfoAvatarUrl="userInfoAvatarUrl"
          :userInfoName="userInfoName"
          :invitationShow="invitationShow"
        ></Header>
        <div class="padB"></div>
        <div class="gap"></div>

        <div class="themeTitBox">
          <span class="themeTit">{{filterInfo.typeWo}}</span>
          <div class="screen" @click="bindScreen">
            <span>筛选</span>
            <span class="icon iconfont icon-down-menu jtGrayB"></span>
            <div class="themeList" v-if="showScreen">
              <a
                href="javascript:;"
                @click="choTheme(item.themeType)"
                v-for="(item,index) in themeChoList"
                :key="index"
              >{{item.typeWo}}</a>
            </div>
          </div>
        </div>
        <!-- <div v-if="themeListCon">
          <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true"></ThemeDet>
        </div>-->
        <div v-if="themeListCon">
          <ThemeDet
            :themeList.sync="themeListCon"
            :isTopShow="true"
            :isMoreShow="true"
            @changeStatus="loadThemeList"
          ></ThemeDet>
        </div>
      </van-pull-refresh>
    </van-list>
    <div class="nullTip" v-if="nullTip">
      <van-icon name="warning-o" size="1.8rem" class="nullIcon" />
      <p class="nullWord">{{nullWord}}</p>
    </div>
    <van-button
      type="primary"
      v-if="loginBtnFix"
      class="loginBtnFix"
      @click="loginJump"
      :style="{'overflow': 'hidden','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2 + 192+'px' : '30%','width': (!isPhone && !isWeixin) ? '256px' : '40%'}"
      :class="{'hide':loginHide}"
    >{{loginWord}}</van-button>
    <!-- <div class="gap"></div> -->
  </div>
</template>
<style scoped="scoped">
.van-pull-refresh {
  min-height: 450px;
}
</style>
<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteOpenCircleCon from "../../../controllers/m_site/circle/openCircleCon";
import mSiteHeader from "../../../controllers/m_site/common/headerCon";
import Header from "../../m_site/common/headerView";
import ThemeDet from "../../m_site/common/themeDetView";
import mSiteThemeDet from "../../../controllers/m_site/common/themeDetCon";
import "../../../defaultLess/m_site/common/common.less";
import "../../../defaultLess/m_site/modules/circle.less";
export default {
  name: "openCircleView",
  components: {
    Header,
    ThemeDet
  },
  ...mSiteHeader,
  ...mSiteThemeDet,
  ...mSiteOpenCircleCon
};
</script>
