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
            <img :src="thread.user._data.avatarUrl" alt="" class="postHead" v-if="thread.user && thread.user._data.avatarUrl">
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
	    <div class="loginOpera" v-if="!alreadyLogin">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin"  v-if="allowRegister">立即注册并加入</a>
	    	<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
	    </div>
		
		<div class="loginOpera" v-else="">
			<p class="welcomeUser">欢迎您，{{loginName}}<a href="javascript:;" class="signOut" @click="signOut">退出</a></p>
			<a href="javascript:;" class="regiJoin" @click="payClick(sitePrice)">付费，获得成员权限</a>
			<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
		</div>
	    <div class="gap"></div>
	    <div class="powerListBox" v-if="limitList">
	    	<div class="powerTit">作为{{limitList._data.name}}，您将获得以下权限</div>
	    	<div class="powerList">
	    		<div class="powerClassify">权限列表</div>
	        <div class="" v-for="(limit,index) in limitList.permission" :key="index">
	          <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewThreads'">查看主题列表<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.viewPosts'">查看主题<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createThread'">发表主题<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.reply'">回复主题<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.0'">上传附件<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.1'">上传图片<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.0'">查看附件<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.1'">查看图片<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewUserList'">站点会员列表<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.delete'">删除附件<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'cash.create'">申请提现<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'order.create'">创建订单<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hide'">删除主题<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hidePosts'">删除回复<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.favorite'">帖子收藏<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.likePosts'">帖子点赞<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.view'">查看某个用户信息权限<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewSiteInfo'">站点信息<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.edit'">编辑用户状态<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'group.edit'">编辑用户组<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createInvite'">管理-邀请加入<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.batchEdit'">批量管理主题<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.editPosts'">编辑<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.essence'">加精<i class="iconfont icon-selected"></i></p>
                <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.sticky'">置顶<i class="iconfont icon-selected"></i></p>
	        </div>
	    	</div>
	    </div>

    </div>
	</van-pull-refresh>

	<van-popup
        v-model="qrcodeShow"
        round
        close-icon-position="top-right"
        closeable
        class="qrCodeBox"
        :z-index="2201"
        get-container="body">
        <span class="popupTit">立即支付</span>
        <div class="payNum">￥<span>{{amountNum}}</span></div>
        <div class="payType">
          <span class="typeLeft">支付方式</span>
          <span class="typeRight"><i class="icon iconfont icon-wepay"></i>微信支付</span>
        </div>
        <img :src="codeUrl" alt="微信支付二维码" class="qrCode">
        <p class="payTip">微信识别二维码支付</p>
      </van-popup>

      <PayMethod
	  	v-if="userDet"
        :data="payList"
        v-model="show"
        :money="sitePrice"
        :balance="walletBalance"
		:walletStatus="userDet._data.canWalletPay"
        payUrl="setup-pay-pwd"
        @oninput="onInput"
        @delete="onDelete"
        @close="onClose"
        :error="errorInfo"
        @payImmediatelyClick="payImmediatelyClick">
      </PayMethod>
	  <div class="loadFix" v-if="payLoading">
        <div class="loadMask"></div>
        <van-loading color="#f7f7f7"  class="loadIcon" type="spinner"/>
      </div>

  </div>
</template>

<script>
import mSitePayCircleConCon from '../../../controllers/m_site/circle/payCircleConCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import PayMethod from '../../../view/m_site/common/pay/paymentMethodView';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
export default {
    name: "payCircleView",
    components:{
		Header,
		PayMethod
    },
    ...mSiteHeader,
    ...mSitePayCircleConCon
}



</script>
