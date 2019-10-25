<!--移动端付费圈子-菜单栏内的邀请模板-->

<template>
    <div class="circleCon">
	    <!-- <Header></Header> -->
	    <div class="headerBox">
	    	<div class="invitePerDet">
	    		<img src="../../../../../../static/images/noavatar.gif" class="inviteHead">
		    	<div class="inviteName">Efhdkjfhk</div>
		    	<p class="inviteWo">邀请您加入</p>
	    	</div>

	        <img src="../../../../../../static/images/logo.png" class="logo padTgap">
	        <div class="circleDet">
	          <span>主题：125</span>
	          <span>成员：125</span>
	          <span>圈主：我是谁</span>
	        </div>
      	</div>
	    <div class="gap"></div>
	    <div class="circleInfo padB0">
	    	<h1 class="cirInfoTit">圈子简介</h1>
	    	<p class="cirInfoWord borNone">Crossday Discuz! Board（简称 Discuz!）是北京康盛新创科技有限责任公司推出的一套通用的社区论坛软件系统。自2001年6月面世以来，Discuz!已拥有15年以上的应用历史和200多万网站用户案例，是全球成熟度最高、覆盖率最大的论坛软件系统之一。目前最新版本Discuz! X3.4正式版于2017年8月2日发布，去除</p>
	    </div>
	    <div class="gap"></div>
	    <div class="powerListBox">
	    	<div class="powerTit">作为成员，您将获得以下权限</div>
	    	<div class="powerList">
	    		<div class="powerClassify">帖子操作</div>
	    		<p class="powerChi">查看主题</p>
	    		<p class="powerChi">发图文帖</p>
	    		<p class="powerChi">付费阅读帖</p>
	    		<p class="powerChi">附件查看</p>
	    		<p class="powerChi">回帖</p>
	    	</div>
	    </div>
		<div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">接受邀请，注册</a>
	    	<p class="payMoney">￥20.00 / 永久有效</p>
	    </div>
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteCircleInviteCon from '../../../controllers/m_site/circle/circleInviteCon';
// import Header from '../../m_site/common//headerView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleInviteView",
    ...mSiteCircleInviteCon
}



</script>
