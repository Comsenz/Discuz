<!--移动端付费站点-菜单栏内的邀请模板-->

<template>
    <div class="circleCon" v-if="siteInfo">
      <!-- <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true" :headFixed="false"></Header> -->
      <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="false" :invitePerDet="true"></Header>
	    <div class="gap"></div>
      <div class="circleInfo padB0 lastBorNone">
      	<h1 class="cirInfoTit">站点简介</h1>
      	<p class="cirInfoWord">{{siteInfo._data.set_site.site_introduction}}</p>
      </div>
      <div class="gap"></div>
      <div class="powerListBox" v-if="limitList">
      	<div class="powerTit">作为{{limitList._data.name}}，您将获得以下权限</div>
      	<div class="powerList">
      		<div class="powerClassify">权限列表</div>
      	  <div class="" v-for="(limit,index) in limitList.permission" :key="index">
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewThreads'">查看主题列表</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.viewPosts'">查看主题</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createThread'">发表主题</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.reply'">回复主题</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.0'">上传附件</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.1'">上传图片</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.0'">查看附件</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.1'">查看图片</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewUserList'">站点会员列表</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.delete'">删除附件</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'cash.create'">申请提现</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'order.create'">创建订单</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hide'">删除主题</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hidePosts'">删除回复</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.favorite'">帖子收藏</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.likePosts'">帖子点赞</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.view'">查看某个用户信息权限</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewSiteInfo'">站点信息</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.edit'">编辑用户状态</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'group.edit'">编辑用户组</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createInvite'">管理-邀请加入</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.batchEdit'">批量管理主题</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.editPosts'">编辑</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.essence'">加精</p>
      	    <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.sticky'">置顶</p>
      	  </div>
      	</div>
      </div>
      <div class="gap"></div>
      <div class="loginOpera">
        <a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
        <a href="javascript:;" @click="registerJump" class="regiJoin" v-if="allowRegister">接受邀请，注册</a>
        <p class="payMoney">￥{{siteInfo._data.set_site.site_price}} / 永久有效</p>
      </div>
    </div>
</template>

<script>
import mSiteCircleInviteCon from '../../../controllers/m_site/circle/circleInviteCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
// import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "circleInviteView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteCircleInviteCon
}



</script>
