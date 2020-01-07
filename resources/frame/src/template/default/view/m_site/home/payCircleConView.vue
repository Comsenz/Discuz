<!--移动端付费站点模板-->

<template>
    <div class="circleCon">
	    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <Header></Header>
	    <!-- <Header :logoShow="true" :perDetShow="true" :userInfoName="false" :invitationShow="false" @categoriesChoice=""></Header> -->
      <!-- <Header :logoShow="true" :perDetShow="true" :invitePerDet="false" :invitationShow="false"></Header> -->
    <div class="circleCon" v-if="thread">
      <Header :logoShow="true" :perDetShow="true" :invitePerDet="false" :invitationShow="false"></Header>
	    <div class="gap"></div>
	    <div class="cirPostCon">
        <!-- <main class="reward-main">
          <div class="reward-con cell-crossing" v-model="thread">
            <ContHeader
              :imgUrl="thread"
              :stateTitle=""
              :time="$moment(thread.firstPost._data.createdAt).fromNow()"
              :userName="thread.firstPost._data.user_name">
            </ContHeader>
              <div class="reference">
              <div class="reference-cont">
                <span>{{thread.firstPost._data.content}}</span>
              </div>
            </div>
          </div>
        </main> -->
		    <div class="postTop">
	    		<div class="postPer">
            <img :src="thread.user._data.avatarUrl" alt="" class="postHead" v-if="thread.user && themeCon.user._data.avatarUrl == '' && thread.user._data.avatarUrl == null">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
	    			<div class="perDet">
	    				<div class="perName" v-if="thread.user">{{thread.user._data.username}}</div>
	    			  <div class="perName" v-else="">该用户已被删除</div>
	    				<div class="postTime" v-if="thread._data.createdAt">{{thread._data.createdAt}}</div>
	    			</div>
	    		</div>
	    	</div>
	    	<div class="postContent">
	    		<a href="javascript:;" v-if="thread.firstPost._data.content" v-html="thread.firstPost._data.content"></a>
	    	</div>
    	</div>
    	<div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">立即注册并加入</a>
	    	<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
	    </div>
	    <div class="gap"></div>
	    <div class="powerListBox" v-if="limitList">
	    	<div class="powerTit">作为{{limitList._data.name}}，您将获得以下权限</div>
	    	<div class="powerList">
	    		<div class="powerClassify">权限列表</div>
	        <div class="" v-for="(limit,index) in limitList.permission">
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
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.deletePosts'">删除回复</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.favorite'">帖子收藏</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.likePosts'">帖子点赞</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.view'">查看某个用户信息权限</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewSiteInfo'">站点信息</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.edit'">编辑用户状态（例如：禁用）</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'group.edit'">编辑用户组</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createInvite'">管理-邀请加入</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hide'">批量删除帖子</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.editPosts'">编辑</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.essence'">加精</p>
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.sticky'">置顶</p>
	        </div>
	    	</div>
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
