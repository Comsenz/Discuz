<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <Header :searchIconShow="true" :perDetShow="true" :logoShow="true" :menuIconShow="true" :navShow="true" :invitePerDet="false" :headFixed="true" :themeNavList="themeNavListCon"></Header>
	    <div class="padB"></div>
      <div class="gap"></div>
	    <div class="themeTitBox">
	    	<span class="themeTit">全部主题</span>
	    	<div class="screen" @click="bindScreen">
	    		<span>筛选</span>
	    		<span class="icon iconfont icon-down-menu jtGrayB"></span>
	    		<div class="themeList" v-if="showScreen">
	    			<a href="javascript:;"  @click="choTheme" v-for="(item,index) in themeChoList" :key="index">{{item.typeWo}}</a>
	    		</div>
	    	</div>
	    </div>
	    <!-- <div class="cirPostCon">
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
	    	</div>
	    </div> -->
      <!-- <div class="cirPostCon"v-for="(items,index) in themeList" :key="index">
        <div class="postTop">
          <div class="postPer">
            <img :src="items.postHead" src="../../../../../../static/images/noavatar.gif" class="postHead">
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
      </div> -->

      <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true"></ThemeDet>
      <div class="gap"></div>
	    <van-button type="primary" v-show="loginBtnFix" class="loginBtnFix" @click="loginJump(1)">登录 / 注册</van-button>
	    <!-- <div class="gap"></div> -->
	    <!-- <div class="" :class="{'fixedFoot': isfixFoot}">
		    <transition name="fade">
			    <div class="loginOpera" v-if="footShow">
			    	<a href="javascript:;" @click="loginJump" class="mustLogin">立即登录</a>
			    	<a href="javascript:;" @click="registerJump" class="regiJoin">注册，并加入</a>
			    </div>
		    </transition>
	    </div> -->
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import '../../../vantJS/m_site/tabs/Title.js';
import mSiteCircleCon from '../../../controllers/m_site/circle/circleCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
import ThemeDet from '../../m_site/common/themeDetView';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "circleView",
    components:{
    	Header,
      ThemeDet
    },
    ...mSiteHeader,
    ...mSiteThemeDet,
    ...mSiteCircleCon
}



</script>
