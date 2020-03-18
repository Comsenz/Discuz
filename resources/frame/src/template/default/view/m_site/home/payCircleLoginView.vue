<!--移动端付费站点-已登录-未付费模板-->

<template>
  <div class="circleCon" v-else-if="situation2">
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <div v-if="siteInfo">
        <Header :logoShow="true" :perDetShow="true"></Header>
        <div class="gap"></div>
        <div class="circlePL">
          <div class="circleLoBox">
            <span class="circleIcon">站点图标</span>
            <img
              v-if="siteInfo._data.set_site.site_logo"
              :src="siteInfo._data.set_site.site_logo"
              class="circleLogo"
            />
            <img v-else :src="appConfig.staticBaseUrl+'/images/logo.png'" class="circleLogo" />
          </div>
        </div>
        <div class="circleInfo padB0 lastBorNone">
          <h1 class="cirInfoTit">站点简介</h1>
          <p class="cirInfoWord">{{siteInfo._data.set_site.site_introduction}}</p>
          <div class="infoItem">
            <span class="infoItemLeft">创建时间</span>
            <span class="infoItemRight">{{siteInfo._data.set_site.site_install}}</span>
          </div>
          <div class="infoItem">
            <span class="infoItemLeft">加入方式</span>
            <!--<span class="infoItemRight">付费{{siteInfo._data.set_site.site_price}}元，有效期自加入起{{siteInfo._data.set_site.site_expire}}天</span>-->
            <span
              class="infoItemRight"
            >付费{{siteInfo._data.set_site.site_price}}元，{{siteInfo._data.set_site.site_expire === '0' || siteInfo._data.set_site.site_expire === ''?'永久加入':'有效期自加入起'+ siteInfo._data.set_site.site_expire +'天'}}</span>
          </div>
          <div class="infoItem">
            <span class="infoItemLeft">站长</span>
            <span class="infoItemRight">{{siteUsername}}</span>
          </div>
          <div class="infoItem">
            <div class="overHide">
              <span class="infoItemLeft">站点成员</span>
            </div>
            <div class="circleMemberList">
              <img
                v-for="(item,index) in siteInfo.users"
                :key="item._data.avatarUrl"
                :src="item._data.avatarUrl"
                :alt="item._data.username"
                class="circleMember"
                v-if="item._data.avatarUrl !== '' && item._data.avatarUrl !== null"
              />
              <img
                :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
                class="circleMember"
                v-else
              />
            </div>
          </div>
        </div>
        <div class="gap"></div>
        <div class="loginOpera">
          <p class="welcomeUser">
            欢迎您，{{loginUserInfo}}
            <a href="javascript:;" class="signOut" @click="signOut">退出</a>
          </p>
          <a href="javascript:;" class="regiJoin" @click="payClick(sitePrice)">付费，获得成员权限</a>
          <p class="payMoney">￥{{sitePrice}} / 永久有效</p>
        </div>

        <van-popup
          v-model="qrcodeShow"
          round
          close-icon-position="top-right"
          closeable
          class="qrCodeBox"
          get-container="body"
        >
          <span class="popupTit">立即支付</span>
          <div class="payNum">
            ￥
            <span>{{amountNum}}</span>
          </div>
          <div class="payType">
            <span class="typeLeft">支付方式</span>
            <span class="typeRight">
              <i class="icon iconfont icon-wepay"></i>微信支付
            </span>
          </div>
          <img :src="codeUrl" alt="微信支付二维码" class="qrCode" />
          <p class="payTip">微信识别二维码支付</p>
        </van-popup>

        <PayMethod
          :data="payList"
          v-model="show"
          :pwd-value="value"
          :money="sitePrice"
          :balance="walletBalance"
          @oninput="onInput"
          @delete="onDelete"
          @close="onClose"
          :error="errorInfo"
          :wallet-status="walletStatus"
          @payImmediatelyClick="payImmediatelyClick"
        ></PayMethod>

        <div class="loadFix" v-if="payLoading">
          <div class="loadMask"></div>
          <van-loading color="#f7f7f7" class="loadIcon" type="spinner" />
        </div>
      </div>
    </van-pull-refresh>
  </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteHeader from "../../../controllers/m_site/common/headerCon";
import mSitePayCircleLoginCon from "../../../controllers/m_site/circle/payCircleLoginCon";
import Header from "../../m_site/common//headerView";
import "../../../defaultLess/m_site/common/common.less";
import "../../../defaultLess/m_site/modules/circle.less";
export default {
  name: "payCircleView",
  components: {
    Header
  },
  ...mSiteHeader,
  ...mSitePayCircleLoginCon
};
</script>
