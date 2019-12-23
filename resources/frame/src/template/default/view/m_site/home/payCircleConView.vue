<!--移动端付费站点模板-->

<template>
    <div class="circleCon">
	    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <Header></Header>
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

	    	</div>
	    	<div class="postContent">
	    		<a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
	    	</div>
    	</div>
    	<div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">立即注册并加入</a>
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

    </van-pull-refresh> 
    </div>
</template>

<script>
import mSitePayCircleConCon from '../../../controllers/m_site/circle/payCircleConCOn';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common//headerView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "payCircleView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSitePayCircleConCon
}



</script>
