<!--移动端付费站点-菜单栏内的邀请模板-->

<template>
    <div class="circleCon" v-if="siteInfo">
      <!-- <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true" :headFixed="false"></Header> -->
      <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true"></Header>
	    <div class="gap"></div>
      <div class="circleInfo padB0 lastBorNone">
      	<h1 class="cirInfoTit">站点简介</h1>
      	<p class="cirInfoWord">{{siteInfo._data.siteIntroduction}}</p>
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
        <p class="payMoney">￥{{siteInfo._data.sitePrice}} / 永久有效</p>
      </div>
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
