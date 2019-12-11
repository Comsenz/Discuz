<!--移动端付费站点模板-->

<template>
    <div class="circleCon">
	    <comHeader title="站点信息"></comHeader>
	    <div class="content">
	    	<div class="circlePL">
		    	<div class="infoItem">
			    	<span class="infoItemLeft">站点名称</span>
			    	<span class="infoItemRight">{{siteInfo.siteName()}}</span>
			    </div>
		    </div>
		    <div class="circlePL">
		    	<div class="circleLoBox">
			    	<span class="circleIcon">站点图标</span>
            <img v-if="siteInfo.logo()" :src="siteInfo.logo()" class="circleLogo">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="circleLogo">
			    </div>
		    </div>
		    <div class="circleInfo padB0 lastBorNone">
		    	<h1 class="cirInfoTit">站点简介</h1>
		    	<p class="cirInfoWord">{{siteInfo.siteIntroduction()}}{{}}</p>
		    	<div class="infoItem">
			    	<span class="infoItemLeft">创建时间</span>
			    	<span class="infoItemRight">{{siteInfo.siteInstall()}}</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">加入方式</span>
			    	<span class="infoItemRight">付费{{siteInfo.price()}}元，有效期自加入起{{siteInfo.day()}}天</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">站长</span>
			    	<span class="infoItemRight">{{username}}</span>
			    </div>
			    <div class="infoItem">
			    	<div class="overHide">
			    		<span class="infoItemLeft">站点成员</span>
			    		<a href="javascript:;" class="infoItemRight lookMore" @click="moreCilrcleMembers">查看更多<span class="icon iconfont icon-right-arrow"></span></a>
			    	</div>
			    	<div class="circleMemberList">
              <img v-for="(item,index) in siteInfo.users()" :key="index" :src="item.avatarUrl()" alt="" class="circleMember" v-if="item.avatarUrl() == '' && item.avatarUrl() == null" @click="membersJump(item.id())">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="circleMember" v-else="" @click="membersJump(item.id())">
			    	</div>
			    </div>
		    </div>
		    <div class="gap"></div>
		    <div class="circleInfo padT0">
		    	<div class="infoItem">
			    	<span class="infoItemLeft">我的角色</span>
			    	<span class="infoItemRight">成员</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">加入时间</span>
			    	<span class="infoItemRight">2020-01-01</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">有效期至</span>
			    	<span class="infoItemRight">2020-01-01</span>
			    </div>
		    </div>
		    <div class="infoPower">
		    	<div class="powerList">
		    		<div class="powerClassify">我的权限</div>
		    		<p class="powerChi">查看主题</p>
		    		<p class="powerChi">发图文帖</p>
		    		<p class="powerChi">付费阅读帖</p>
		    		<p class="powerChi">附件查看</p>
		    		<p class="powerChi">回帖</p>
		    	</div>
		    </div>
	    </div>
    </div>
</template>

<script>
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import mSiteCircleInfoCon from '../../../controllers/m_site/management/circleInfoCon';

import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleInfoView",
    components:{
      comHeader
    },
    ...mSiteCircleInfoCon

}



</script>
