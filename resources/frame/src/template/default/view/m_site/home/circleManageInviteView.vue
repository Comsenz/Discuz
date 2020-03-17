<!--移动端站点管理里的邀请模板-->

<template>
    <div class="circleCon" v-if="siteInfo">
		<van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true"></Header>
      <div class="gap"></div>
      <div class="circleInfo padB0 lastBorNone">
      	<h1 class="cirInfoTit">站点简介</h1>
      	<p class="cirInfoWord">{{siteInfo._data.set_site.site_introduction}}</p>
      </div>
	    <!-- <div class="circleInfo padB0">
	    	<h1 class="cirInfoTit">站点简介</h1>
	    	<p class="cirInfoWord borNone">Crossday Discuz! Board（简称 Discuz!）是北京康盛新创科技有限责任公司推出的一套通用的社区论坛软件系统。自2001年6月面世以来，Discuz!已拥有15年以上的应用历史和200多万网站用户案例，是全球成熟度最高、覆盖率最大的论坛软件系统之一。目前最新版本Discuz! X3.4正式版于2017年8月2日发布，去除</p>
	    </div> -->
	    <div class="gap"></div>
	    <div class="powerListBox">
	    	<div class="powerTit">作为{{roleResult}}，您将获得以下权限</div>
	    	<div class="powerList">
	    		<div class="powerClassify">帖子操作</div>
	    		<p class="powerChi">查看主题<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">发图文帖<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">付费阅读帖<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">附件查看<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">回帖<i class="iconfont icon-selected"></i></p>
	    	</div>
	    	<div class="powerList">
	    		<div class="powerClassify">站点前台管理</div>
	    		<p class="powerChi">编辑站点<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">邀请加入<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">标签管理<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">用户管理<i class="iconfont icon-selected"></i></p>
	    	</div>
	    	<div class="powerList">
	    		<div class="powerClassify">前台内容管理</div>
	    		<p class="powerChi">置顶<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">加精<i class="iconfont icon-selected"></i></p>
	    		<p class="powerChi">删帖<i class="iconfont icon-selected"></i></p>
	    	</div>
	    </div>
      <div class="gap"></div>
      <div class="loginOpera">
        <a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
        <a href="javascript:;" @click="registerJump" class="regiJoin" v-if="allowRegister">接受邀请，注册</a>
        <p v-if="siteInfo._data.set_site.site_price" class="payMoney">￥{{siteInfo._data.set_site.site_price}} / 永久有效</p>
      </div>
	</van-pull-refresh>
    </div>
</template>

<script>
import Header from '../../m_site/common/headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSiteCircleManageInviteCon from '../../../controllers/m_site/circle/circleManageInviteCon';
import '../../../defaultLess/m_site/common/common.less';
import '../../../defaultLess/m_site/modules/circle.less';
import '../../../defaultLess/m_site/modules/manageCircle.less';
export default {
    name: "circleInviteView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteCircleManageInviteCon
}



</script>
