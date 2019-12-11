<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <!-- <Header :searchIconShow="true" :perDetShow="true" :logoShow="true" :menuIconShow="true" :navShow="true" :invitePerDet="false" :headFixed="true" :themeNavList="themeNavListCon" ></Header> -->
	    <div class="padB"></div>
      <div class="gap"></div>
	    <div class="themeTitBox">
	    	<span class="themeTit">全部主题</span>
	    	<div class="screen" @click="bindScreen">
	    		<span>筛选</span>
	    		<span class="icon iconfont icon-down-menu jtGrayB"></span>
	    		<div class="themeList" v-if="showScreen">
	    			<a href="javascript:;"  @click="choTheme" v-for="(item,index) in themeChoList" :key="index">{{item.typeWo}}</a>
	    		</div>
	    	</div>
	    </div>
      <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true"></ThemeDet>
      <div class="gap"></div>
	    <van-button type="primary" v-show="loginBtnFix" class="loginBtnFix" @click="loginJump(1)">登录 / 注册</van-button>
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
