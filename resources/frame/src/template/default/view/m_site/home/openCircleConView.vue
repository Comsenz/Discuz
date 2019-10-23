<!--移动端首页模板-->

<template>
    <div class="circleCon">
    	<div class="contentHead">
    		<span class="icon iconfont icon-back headBack"></span>
    		<h1 class="headTit">详情</h1>
    	</div>
    	<div class="content">
		    <div class="cirPostCon">
		    	<div class="postTop">
		    		<div class="postPer">
		    			<img src="" class="postHead">
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
		    	<div class="postImgBox oneImgBox">
		    		<div class="oneImg">
		    			<img src="" class="postPictures">
		    		</div>
		    	</div>
		    	<div class="postImgBox twoImgBox">
		    		<div class="twoImg">
		    			<img src="" class="postPictures">
		    		</div>
		    		<div class="twoImg">
		    			<img src="" class="postPictures">
		    		</div>
		    	</div>
		    	<div class="postImgBox threeImgBox">
		    		<div class="threeImg">
		    			<img src="" class="postPictures">
		    		</div>
		    		<div class="threeImg">
		    			<img src="" class="postPictures">
		    		</div>
		    		<div class="threeImg">
		    			<img src="" class="postPictures">
		    		</div>
		    	</div>
		    	<div class="uploadFileList">
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont"></span>
		    			<span class="fileName">文档名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont"></span>
		    			<span class="fileName">文档名称称名称名称.doc</span>
		    		</a>
		    	</div>


		    </div>
	    
	    </div>
	    <div class="gap"></div>    
	    <div class="loginOpera">
	    	<a href="javascript:;" class="mustLogin">立即登录</a>
	    	<a href="javascript:;" class="regiJoin">注册，并加入</a>
	    </div>
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteCircleCon from '../../../controllers/m_site/circle/circleCon';
import Header from '../../m_site/common//headerView';
import  '../../../scss/mobile/mobileIndex.scss';
export default {
    name: "circleView",
    components:{
    	Header
    },
    ...mSiteCircleCon
}



</script>
