<!--移动端付费站点-已登录-未付费模板-->

<template>
  <div class="circleCon" v-else-if="situation2">
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    <div v-if="siteInfo">
      <Header :logoShow="true" :perDetShow="true"></Header>
      <div class="gap"></div>
      <div class="circlePL">
      	<div class="circleLoBox">
        	<span class="circleIcon">站点图标</span>
          <img v-if="siteInfo.logo" :src="siteInfo._data.logo" class="circleLogo">
          <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="circleLogo">
        </div>
      </div>
      <div class="circleInfo padB0 lastBorNone">
      	<h1 class="cirInfoTit">站点简介</h1>
      	<p class="cirInfoWord">{{siteInfo._data.siteIntroduction}}</p>
      	<div class="infoItem">
        	<span class="infoItemLeft">创建时间</span>
        	<span class="infoItemRight">{{siteInfo._data.siteInstall}}</span>
        </div>
        <div class="infoItem">
        	<span class="infoItemLeft">加入方式</span>
        	<span class="infoItemRight">付费{{siteInfo._data.sitePrice}}元，有效期自加入起{{siteInfo._data.siteExpire}}天</span>
        </div>
        <div class="infoItem">
        	<span class="infoItemLeft">站长</span>
        	<span class="infoItemRight">{{siteUsername}}</span>
        </div>
        <div class="infoItem">
        	<div class="overHide">
        		<span class="infoItemLeft">站点成员</span>
        	</div>
        	<div class="circleMemberList">
            <img v-for="(item,index) in siteInfo.users" :key="index" :src="item._data.avatarUrl" alt="" class="circleMember" v-if="item._data.avatarUrl == '' && item._data.avatarUrl == null">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="circleMember" v-else="">
        	</div>
        </div>
      </div>
      <div class="gap"></div>
      <div class="loginOpera">
      	<p class="welcomeUser">欢迎您，{{username}}<a href="javascript:;" class="signOut" @click="signOut">退出</a></p>
      	<a href="javascript:;" class="regiJoin" @click="sitePayClick(sitePrice)">付费，获得成员权限</a>
      	<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
      </div>
    </div>
 </van-pull-refresh>
  </div>

</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSitePayCircleLoginCon from '../../../controllers/m_site/circle/payCircleLoginCon';
import Header from '../../m_site/common//headerView';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "payCircleView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSitePayCircleLoginCon
}



</script>
