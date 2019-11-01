<!--移动端付费圈子模板-->

<template>
    <div class="circleCon">
	    <Header></Header>
	    <div class="content">
	    	<div class="circleInfo">
		    	<div class="infoItem">
			    	<span class="infoItemLeft">圈子名称</span>
			    	<span class="infoItemRight">Discuz!Q</span>
			    </div>
		    </div>
	    	<div class="circleLoBox">
		    	<span class="circleIcon">圈子图标</span>
		    	<img src="../../../../../../static/images/logo.png" class="circleLogo">
		    </div>
		    <div class="circleInfo">
		    	<h1 class="cirInfoTit">圈子简介</h1>
		    	<p class="cirInfoWord">Crossday Discuz! Board（简称 Discuz!）是北京康盛新创科技有限责任公司推出的一套通用的社区论坛软件系统。自2001年6月面世以来，Discuz!已拥有15年以上的应用历史和200多万网站用户案例，是全球成熟度最高、覆盖率最大的论坛软件系统之一。目前最新版本Discuz! X3.4正式版于2017年8月2日发布，去除</p>
		    	<div class="infoItem">
			    	<span class="infoItemLeft">创建时间</span>
			    	<span class="infoItemRight">2018-1-1</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">加入方式</span>
			    	<span class="infoItemRight">付费20元，有效期自加入起365天</span>
			    </div>
			    <div class="infoItem">
			    	<span class="infoItemLeft">圈主</span>
			    	<span class="infoItemRight">我是凌凌漆</span>
			    </div>
			    <div class="infoItem">
			    	<div class="overHide">
			    		<span class="infoItemLeft">圈子成员</span>
			    		<a href="javascript:;" class="infoItemRight lookMore">查看更多<span class="icon iconfont icon-right-arrow"></span></a>
			    	</div>
			    	<div class="circleMemberList">
			    		<img src="../../../../../../static/images/noavatar.gif" class="circleMember">
			    		<img src="../../../../../../static/images/noavatar.gif" class="circleMember">
			    		<img src="../../../../../../static/images/noavatar.gif" class="circleMember">
			    		<img src="../../../../../../static/images/noavatar.gif" class="circleMember">
			    		<img src="../../../../../../static/images/noavatar.gif" class="circleMember">
			    	</div>
			    </div>
		    </div>
		    <div class="gap"></div>
		    <div class="circleInfo">
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
import mSiteCircleInfoCon from '../../../controllers/m_site/management/circleInfoCon';
// import mSiteHeader from '../../../controllers/m_site/common/headerCon';
// import Header from '../../m_site/common//headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleInfoView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteCircleInfoCon
}



</script>
