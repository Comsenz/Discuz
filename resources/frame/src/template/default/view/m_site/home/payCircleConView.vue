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
	    <div class="cirPostCon">
		    <div class="postTop">
	    		<div class="postPer">
	    			<img src="" class="postHead">
	    			<div class="perDet">
	    				<div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
	    				<div class="postTime">1小时前</div>
	    			</div>
	    		</div>
	    		
	    	</div>
	    	<div class="postContent">
	    		<a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
	    	</div>
    	</div>
    	<div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" class="mustLogin">立即登录</a>
	    	<a href="javascript:;" class="regiJoin">注册，并加入</a>
	    	<p class="payMoney">￥20.00 / 永久有效</p>
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
