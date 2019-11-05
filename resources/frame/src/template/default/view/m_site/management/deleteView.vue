<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <Header></Header>
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
	    <div class="memberCheckList">
		    <van-checkbox-group v-model="result" ref="checkboxGroup" class="deleChi" v-for="(items,index) in themeList" :key="index">
			    <div class="cirPostCon">
			    	<div class="postTop">
			    		<div class="postPer">
			    			
			    			<img :src="items.postHead" class="postHead">
			    			<div class="perDet">
			    				<div class="perName">{{items.postName}}</div>
			    				<div class="postTime">{{items.postTime}}</div>
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
			    		<a href="javascript:;">{{items.postCon}}</a>
			    	</div>
			    	<div class="operaBox">
			    		<div class="likeBox">
			    			<span class="icon iconfont icon-praise-after"></span>
			    			<i></i><a href="javascript:;" v-for="fabulous in items.fabulousList">{{fabulous+','}}</a>&nbsp;等<span>{{items.fabulousNum}}</span>个人觉得很赞
			    		</div>
			    		<div class="reward">
			    			<span class="icon iconfont icon-money"></span>
			    			<a href="javascript:;" v-for="reward in items.rewardList">{{reward+','}}</a>
			    		</div>
			    		<div class="replyBox">
			    			<div class="replyCon" v-for="comment in items.commentList">
			    				<a href="javascript:;">{{comment.commentName}}</a>:
			    				<span>{{comment.commentWo}}</span>
			    			</div>
			    			<div class="replyCon" v-for="reply in items.replyList">
			    				<a href="javascript:;">{{reply.replyName}}</a>
			    				<span class="font9">回复</span>
			    				<a href="javascript:;">{{reply.commentsName}}</a>:
			    				<span>{{reply.replyWo}}</span>
			    			</div>
			    			<a href="javascript;" class="allReply">全部27条回复<span class="icon iconfont icon-right-arrow"></span></a>
			    		</div>
			    	</div>
					<van-checkbox v-model="items.checked"></van-checkbox>
			    </div>
			    <div class="gap"></div>

		    </van-checkbox-group>



	    </div>
	    <div class="manageFootFixed choFixed">
          <a href="javascript:;" @click="checkAll">全选</a>
			<a href="javascript:;" @click="signOutDele">退出批量删除</a>
			<button class="checkSubmit">删除选中</button>
		</div>

    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import '../../../vantJS/m_site/tabs/Title.js';
import mSiteDeleteCon from '../../../controllers/m_site/management/deleteCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "circleView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteDeleteCon
}



</script>
