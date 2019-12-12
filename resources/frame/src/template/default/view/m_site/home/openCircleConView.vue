<!--移动端首页模板-->

<template>
    <div class="circleCon">
    	<!-- <div class="contentHead">
    		<span class="icon iconfont icon-back headBack"></span>
    		<h1 class="headTit">详情</h1>
    	</div> -->
    	<!-- <Header></Header> -->
		<comHeader title="详情"></comHeader>
    	<div class="content">
		    <div class="cirPostCon">
		    	<div class="postTop">
		    		<div class="postPer">
		    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
		    			<div class="perDet">
		    				<div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
		    				<div class="postTime">1小时前</div>
		    			</div>
		    		</div>
		    		<div class="postOpera">
		    			<span class="icon iconfont icon-top"></span>
		    		</div>
		    	</div>
		    	<div class="postContent">
		    		<a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
		    	</div>
		    	<div class="postImgBox">
		    		<div class="postImgList">
		    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postPictures">
		    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postPictures">
		    		</div>
		    	</div>
		    	<div class="uploadFileList">
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-pdf"></span>
		    			<span class="fileName">文档名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-rar"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-word"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-pdf"></span>
		    			<span class="fileName">文档名称称名称名称.doc</span>
		    		</a>
		    	</div>
		    	<div class="postDetBot">
		    		<span class="readNum">235阅读</span>
		    		<a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a>
		    		<a href="javascript:;" class="postDetR">收藏</a>
		    		<a href="javascript:;" class="postDetR">分享</a>
		    	</div>
		    </div>
	    </div>
	    <div class="gap"></div>
	    <div class="commentBox">
	    	<div class="likeBox">
    			<span class="icon iconfont icon-praise-after"></span>
    			<a href="javascript:;">Elizabetch</a>，<a href="javascript:;">sdfdsfsd</a>，<a href="javascript:;">第三方第三方</a>，<a href="javascript:;">电风扇</a>，<a href="javascript:;">dfffss</a>&nbsp;等<span>21</span>个人觉得很赞
    		</div>
    		<div class="payPer">
    			<span class="icon iconfont icon-money"></span>
    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
    		</div>
    		<div class="commentPostDet">
	    		<div class="postTop">
		    		<div class="postPer">
		    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
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
		    <div class="commentOpera padT22">
	    		<a href="">管理<span class="icon iconfont icon-down-menu"></span></a>
	    		<a href=""><span class="icon iconfont icon-praise-after"></span>22</a>
	    		<a class="icon iconfont icon-review"></a>
	    	</div>
		    <div class="commentPostDet">
		    	<div class="postTop quotePostTop">
		    		<div class="postPer">
		    			<img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
		    			<div class="perDet">
		    				<div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
		    				<div class="postTime">1小时前</div>
		    			</div>
		    		</div>
	    		</div>
	    		<div class="quoteCon">
	    			我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。
	    		</div>
	    		<p class="viewPoint">我的观点不一样</p>
	    	</div>
	    	<div class="commentOpera afterNone">
	    		<a href="">管理<span class="icon iconfont icon-down-menu"></span></a>
	    		<a href=""><span class="icon iconfont icon-praise-after"></span>22</a>
	    		<a class="icon iconfont icon-review"></a>
	    	</div>
	    </div>
	    <!-- <div class="gap"></div> -->
	    <!-- <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">立即登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">注册</a>
	    </div> -->
      <van-button type="primary" v-show="loginBtnFix" class="loginBtnFix">登录 / 注册</van-button>
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import mSiteCircleCon from '../../../controllers/m_site/circle/circleCon';
import mSiteOpenCircleConCon from '../../../controllers/m_site/circle/openCircleConCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "openCircleConView",
    components:{
    	comHeader
    },
    ...mSiteOpenCircleConCon
}



</script>
