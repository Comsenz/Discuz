<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <Header :searchIconShow="true" :perDetShow="true" :logoShow="true" :menuIconShow="true" :navShow="true" :invitePerDet="false" :headFixed="true" @categoriesChoice="categoriesChoice"></Header>
	    <div class="padB"></div>
      <div class="gap"></div>
	   <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check="false"
    >
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
	    </van-list>
	    <van-button type="primary" v-if="loginBtnFix" class="loginBtnFix" @click="loginJump(1)" :class="{'hide':loginHide}">登录 / 注册</van-button>
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
