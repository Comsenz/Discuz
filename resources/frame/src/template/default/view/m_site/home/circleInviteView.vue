<!--移动端付费站点-菜单栏内的邀请模板-->

<template>
    <div class="circleCon">
      <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true" :headFixed="false"></Header>
	    <div class="gap"></div>
      <div class="circleInfo padB0 lastBorNone">
      	<h1 class="cirInfoTit">站点简介</h1>
      	<p class="cirInfoWord">{{siteInfo.siteIntroduction()}}{{}}</p>
      	<div class="infoItem">
        	<span class="infoItemLeft">创建时间</span>
        	<span class="infoItemRight">{{siteInfo.siteInstall()}}</span>
        </div>
        <div class="infoItem">
        	<span class="infoItemLeft">加入方式</span>
        	<span class="infoItemRight">付费{{siteInfo.price()}}元，有效期自加入起{{siteInfo.day()}}天</span>
        </div>
        <div class="infoItem">
        	<span class="infoItemLeft">站长</span>
        	<span class="infoItemRight">{{username}}</span>
        </div>
        <div class="infoItem">
        	<div class="overHide">
        		<span class="infoItemLeft">站点成员</span>
        		<a href="javascript:;" class="infoItemRight lookMore" @click="moreCilrcleMembers">查看更多<span class="icon iconfont icon-right-arrow"></span></a>
        	</div>
        	<div class="circleMemberList">
            <img v-for="(item,index) in siteInfo.users()" :key="index" :src="item.avatarUrl()" alt="" class="circleMember" v-if="item.avatarUrl() == '' && item.avatarUrl() == null" @click="membersJump(item.id())">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="circleMember" v-else="" @click="membersJump(item.id())">
        	</div>
        </div>
      </div>
      <div class="gap"></div>
      <div class="circleInfo padT0">
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
	    <!-- <div class="circleInfo padB0">
	    	<h1 class="cirInfoTit">站点简介</h1>
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
    </div> -->
    </div>
</template>

<script>
import mSiteCircleInviteCon from '../../../controllers/m_site/circle/circleInviteCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleInviteView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteCircleInviteCon
}



</script>
