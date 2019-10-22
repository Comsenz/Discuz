<!--移动端付费圈子模板-->

<template>
    <div class="circleCon">
	    <!-- <Header></Header> -->
	    <div class="headerBox">
	        <!-- <div class="headOpe">
	          <span class="icon iconfont">&#xe60b;</span>
	          <span class="icon iconfont">&#xe60c;</span>
	        </div> -->
	        <img src="../../../../../../static/images/logo.png" class="logo padTgap">
	        <div class="circleDet">
	          <span>主题：125</span>
	          <span>成员：125</span>
	          <span>圈主：我是谁</span>
	        </div>
      	</div>
	    <div class="gap"></div>
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
		    		<img src="" class="circleMember">
		    		<img src="" class="circleMember">
		    		<img src="" class="circleMember">
		    		<img src="" class="circleMember">
		    		<img src="" class="circleMember">
		    	</div>
		    </div>
		    <div class="loginOpera">
		    	<a href="javascript:;" class="mustLogin">立即登录</a>
		    	<a href="javascript:;" class="regiJoin">注册，并加入</a>
		    </div>
	    </div>
	    
	    
	    
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSitePayCircleCon from '../../../controllers/m_site/circle/payCircleCon';
import Header from '../../m_site/common//headerView';
import  '../../../scss/mobile/mobileIndex.scss';
export default {
    name: "payCircleView",
    components:{
    	Header
    },
    ...mSitePayCircleCon
}



</script>
