<!--移动端首页模板-->

<template>
    <div class="circleCon">
    <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check="false"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
     <Header :searchIconShow="false" :perDetShow="true" :logoShow="true" :menuIconShow="false" :navShow="true" :invitePerDet="true" :headFixed="false" @categoriesChoice="categoriesChoice" :userInfoAvatarUrl="userInfoAvatarUrl" :userInfoName="userInfoName" :invitationShow="invitationShow"></Header>
	    <div class="gap"></div>
      <div class="themeTitBox">
      	<span class="themeTit">全部主题</span>
      	<div class="screen" @click="bindScreen">
      		<span>筛选</span>
      		<span class="icon iconfont icon-down-menu jtGrayB"></span>
      		<div class="themeList" v-if="showScreen">
      			<a href="javascript:;"  @click="choTheme(item.themeType)" v-for="(item,index) in themeChoList" :key="index">{{item.typeWo}}</a>
      		</div>
      	</div>
      </div>
      <div v-if="themeListCon">
        <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true"></ThemeDet>
      </div>
  </van-pull-refresh>    
  </van-list>





	    <div class="gap"></div>
	    <div class="loginOpera">
	    	<a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
	    	<a href="javascript:;" @click="registerJump" class="regiJoin">接受邀请，注册</a>
	    </div>

    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteOpenCircleCon from '../../../controllers/m_site/circle/openCircleCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common/headerView';
import ThemeDet from '../../m_site/common/themeDetView';
import mSiteThemeDet from '../../../controllers/m_site/common/themeDetCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "openCircleView",
    components:{
    	Header,
      ThemeDet
    },
    ...mSiteHeader,
    ...mSiteThemeDet,
    ...mSiteOpenCircleCon
}



</script>
