<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <Header></Header>
	    <!-- <div class="headerBox">
	        <div class="headOpe">
	          <span class="icon iconfont">&#xe<b;</span>
	          <span class="icon iconfont">&#xe60c;</span>
	        </div>
	        <img src="../../../../../../static/images/logo.png" class="logo">
	        <div class="circleDet">
	          <span>主题：125</span>
	          <span>成员：125</span>
	          <span>圈主：我是谁</span>
	        </div>
      	</div> -->

      <!-- <div class="navBarBox" id="testNavBar":class="{'fixedNavBar': isfixNav}">
          <ul class="navBarCon">
            <li v-for="(todo, index) in todos" v-on:click="addClass(index,$event)" v-bind:class="{ navActi:index==current}":key="index">{{ todo.text }}</li>
          </ul>
      </div> -->
        <div class="gap"></div>
	    <div class="themeTitBox">
	    	<span class="themeTit">全部主题</span>
	    	<div class="screen" @click="bindScreen">
	    		<span>筛选</span>
	    		<span class="icon iconfont icon-down-menu jtGrayB"></span>
	    		<div class="themeList" v-if="showScreen">
	    			<a href="javascript:;">全部主题</a>
	    			<a href="javascript:;">精华主题</a>
	    		</div>
	    	</div>
	    </div>
	    <div class="cirPostCon">
	    	<div class="postTop">
	    		<div class="postPer">
	    			<img src="../../../../../../static/images/noavatar.gif" class="postHead">
	    			<div class="perDet">
	    				<div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
	    				<div class="postTime">1小时前</div>
	    			</div>
	    		</div>
	    		<div class="postOpera">
	    			<span class="icon iconfont icon-top"></span>
	    			<div class="moreCli">
	    				<span class="icon iconfont icon-more"></span>
	    			</div>
	    		</div>
	    	</div>
	    	<div class="postContent">
	    		<a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
	    	</div>
	    	<div class="operaBox">
	    		<div class="likeBox">
	    			<span class="icon iconfont icon-praise-after"></span>
	    			<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">sdfdsfsd</a>，<a href="javascript:;">第三方第三方</a>，<a href="javascript:;">电风扇</a>，<a href="javascript:;">dfffss</a>&nbsp;等<span>21</span>个人觉得很赞
	    		</div>
	    		<div class="reward">
	    			<span class="icon iconfont icon-money"></span>
	    			<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>
	    		</div>
	    		<div class="replyBox">
	    			<div class="replyCon">
	    				<a href="javascript:;">jsone</a>:
	    				<span>手动给赞，每天被概念搞的晕头转向，几点开始福克斯的机会放大看就看</span>
	    			</div>
	    			<div class="replyCon">
	    				<a href="javascript:;">jsone</a>
	    				<span class="font9">回复</span>
	    				<a href="javascript:;">jsosdsdne</a>:
	    				<span>手动给赞克斯的机会放大看就看</span>
	    			</div>
	    			<a href="javascript;" class="allReply">全部27条回复<span class="icon iconfont icon-right-arrow"></span></a>
	    		</div>
	    		<a href="javascript:;" class="fixedEdit">
	    			<span class="icon iconfont icon-publish"></span>
	    		</a>
	    	</div>
	    </div>
	    <div class="gap"></div>
	    <div class="cirPostCon">
	    	<div class="postTop">
	    		<div class="postPer">
	    			<img src="../../../../../../static/images/noavatar.gif" class="postHead">
	    			<div class="perDet">
	    				<div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
	    				<div class="postTime">1小时前</div>
	    			</div>
	    		</div>
	    		<div class="postOpera">
	    			<span class="icon iconfont icon-top"></span>
	    			<div class="moreCli">
	    				<span class="icon iconfont icon-more"></span>
	    			</div>
	    		</div>
	    	</div>
	    	<div class="postContent">
	    		<a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
	    	</div>
	    	<div class="operaBox">
	    		<div class="likeBox">
	    			<span class="icon iconfont icon-praise-after"></span>
	    			<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">sdfdsfsd</a>，<a href="javascript:;">第三方第三方</a>，<a href="javascript:;">电风扇</a>，<a href="javascript:;">dfffss</a>&nbsp;等<span>21</span>个人觉得很赞
	    		</div>
	    		<div class="reward">
	    			<span class="icon iconfont icon-money"></span>
	    			<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">Elizabetch</a>
	    		</div>
	    		<div class="replyBox">
	    			<div class="replyCon">
	    				<a href="javascript:;">jsone</a>:
	    				<span>手动给赞，每天被概念搞的晕头转向，几点开始福克斯的机会放大看就看</span>
	    			</div>
	    			<div class="replyCon">
	    				<a href="javascript:;">jsone</a>
	    				<span class="font9">回复</span>
	    				<a href="javascript:;">jsosdsdne</a>:
	    				<span>手动给赞克斯的机会放大看就看</span>
	    			</div>
	    			<a href="javascript;" class="allReply">全部27条回复<span class="icon iconfont icon-right-arrow"></span></a>
	    		</div>
	    		<a href="javascript:;" class="fixedEdit">
	    			<span class="icon iconfont icon-publish"></span>
	    		</a>
	    	</div>
	    </div>

	    <div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">立即登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">注册，并加入</a>
	    </div>
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteCircleCon from '../../../controllers/m_site/circle/circleCon';
import mSiteHeader from '../../../controllers/m_site/common/header';
import Header from '../../m_site/common/headerView';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteCircleCon
}



</script>
