<!--移动端首页模板-->

<template>
    <div class="circleCon" v-if="situation1">
	    <Header :searchIconShow="true" :perDetShow="true" :logoShow="true" :menuIconShow="true" :navShow="true" :invitePerDet="false" :headFixed="true" :themeNavList="themeNavListCon" @categoriesChoice="categoriesChoice"></Header>
	    <div class="padB"></div>
      <div class="gap"></div>
	  	<van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <div class="themeTitBox">
	    	<span class="themeTit">全部主题</span>
	    	<div class="screen" @click="bindScreen">
	    		<span>筛选</span>
	    		<span class="icon iconfont icon-down-menu jtGrayB"></span>
	    		<div class="themeList" v-if="showScreen">
	    			<a href="javascript:;"  @click="choTheme(item.themeType)" v-for="(item,index) in themeChoList" :key="index">{{item.typeWo}}</a>
	    		</div>
	    	</div>
	    </div>
      <div v-if="themeListCon">
        <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true"></ThemeDet>
      </div>
      </van-pull-refresh>
	    <van-button type="primary" v-if="loginBtnFix" class="loginBtnFix" @click="loginJump(1)" :class="{'hide':loginHide}">登录 / 注册</van-button>
	    <!-- <div class="gap"></div> -->
	    <!-- <div class="" :class="{'fixedFoot': isfixFoot}">
		    <transition name="fade">
			    <div class="loginOpera" v-if="footShow">
			    	<a href="javascript:;" @click="loginJump" class="mustLogin">立即登录</a>
			    	<a href="javascript:;" @click="registerJump" class="regiJoin">注册，并加入</a>
			    </div>
		    </transition>
	    </div> -->
    </div>

    <!-- 付费站点 ，未登录 -->
    <!-- <div class="circleCon" v-else-if="situation2">
      <div v-if="siteInfo">
        <Header :logoShow="true" :perDetShow="true"></Header>
        <div class="gap"></div>
        <div class="circlePL">
        	<div class="circleLoBox">
          	<span class="circleIcon">站点图标</span>
            <img v-if="siteInfo.logo" :src="siteInfo._data.logo" class="circleLogo">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="circleLogo">
          </div>
        </div>
        <div class="circleInfo padB0 lastBorNone">
        	<h1 class="cirInfoTit">站点简介</h1>
        	<p class="cirInfoWord">{{siteInfo._data.siteIntroduction}}</p>
        	<div class="infoItem">
          	<span class="infoItemLeft">创建时间</span>
          	<span class="infoItemRight">{{siteInfo._data.siteInstall}}</span>
          </div>
          <div class="infoItem">
          	<span class="infoItemLeft">加入方式</span>
          	<span class="infoItemRight">付费{{siteInfo._data.price}}元，有效期自加入起{{siteInfo.day}}天</span>
          </div>
          <div class="infoItem">
          	<span class="infoItemLeft">站长</span>
          	<span class="infoItemRight">{{siteUsername}}</span>
          </div>
          <div class="infoItem">
          	<div class="overHide">
          		<span class="infoItemLeft">站点成员</span>
          		<a href="javascript:;" class="infoItemRight lookMore" @click="moreCilrcleMembers">查看更多<span class="icon iconfont icon-right-arrow"></span></a>
          	</div>
          	<div class="circleMemberList">
              <img v-for="(item,index) in siteInfo.users" :key="index" :src="item._data.avatarUrl" alt="" class="circleMember" v-if="item._data.avatarUrl == '' && item._data.avatarUrl == null" @click="membersJump(item._data.id)">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="circleMember" v-else="" @click="membersJump(item._data.id)">
          	</div>
          </div>
        </div>
        <div class="gap"></div>
        <div class="loginOpera">
        	<p class="welcomeUser">欢迎您，{{username}}<a href="javascript:;" class="signOut" @click="signOut">退出</a></p>
        	<a href="javascript:;" class="regiJoin" @click="sitePayClick(sitePrice)">付费，获得成员权限</a>
        	<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
        </div>
      </div>
      <van-popup
      v-model="qrcodeShow"
      round
      close-icon-position="top-right"
      closeable
      class="qrCodeBox"
      get-container="body">
      <span class="popupTit">立即支付</span>
       <div class="payNum">￥<span>{{amountNum}}</span></div>
       <div class="payType">
         <span class="typeLeft">支付方式</span>
         <span class="typeRight"><i class="icon iconfont icon-wepay"></i>微信支付</span>
       </div>
       <img :src="codeUrl" alt="" class="qrCode">
       <p class="payTip">微信识别二维码支付</p>
      </van-popup>
    </div> -->

    <!-- 付费站点 已登录但未付费 -->
    <div v-else-if="situation3">
       付费站点 未登录
    </div>



    <!-- 付费站点 已登录且已付费 -->
    <!-- <div v-else="situation4">
      付费站点 已登录且已付费
    </div> -->
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import '../../../vantJS/m_site/tabs/Title.js';
import mSiteCircleCon from '../../../controllers/m_site/circle/circleCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
import ThemeDet from '../../m_site/common/themeDetView';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "circleView",
    components:{
    	Header,
      ThemeDet
    },
    ...mSiteHeader,
    ...mSiteThemeDet,
    ...mSiteCircleCon
}



</script>
