<!--移动端首页模板-->

<template>
    <div class="circleCon">
    <van-list
    v-model="loading"
    :finished="finished"
    :offset="offset"
    :finished-text="pageIndex === 1 && themeListCon.length === 0 ? '暂无数据':'没有更多了'"
    @load="onLoad"
    :immediate-check="false"
    >
	  	<van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <Header :searchIconShow="searchStatus" :perDetShow="true" :logoShow="true" :menuIconShow="menuStatus" :navShow="true" :invitePerDet="false" :headFixed="true" @categoriesChoice="categoriesChoice" v-on:update="receive"></Header>
	    <div class="padB"></div>
      <div class="gap"></div>

	    <div class="themeTitBox">
	    	<span class="themeTit">{{filterInfo.typeWo}}</span>
	    	<div class="screen" @click="bindScreen" ref="screenBox">
	    		<span>筛选</span>
	    		<span class="icon iconfont icon-down-menu jtGrayB"></span>
	    		<div class="themeList" v-if="showScreen">
	    			<a href="javascript:;"  @click="choTheme(item.themeType)" v-for="(item,index) in themeChoList" :key="index">{{item.typeWo}}</a>
	    		</div>
	    	</div>
	    </div>
      <div v-if="themeListCon">
        <ThemeDet :themeList.sync="themeListCon" :isTopShow="true" :isMoreShow="true" @changeStatus="loadThemeList"></ThemeDet>
      </div>
      </van-pull-refresh>
	    </van-list>
      <div class="nullTip" v-if="nullTip">
        <van-icon name="warning-o" size="1.8rem" class="nullIcon"/>
        <p class="nullWord">{{nullWord}}</p>
      </div>
      <van-button type="primary" v-if="loginBtnFix" class="loginBtnFix" @click="loginJump(1)" :class="{'hide':loginHide}">{{loginWord}}</van-button>
      <div class="fixedEdit" id="fixedEdit" v-if="canEdit" @click="postTopic">
        <span class="icon iconfont icon-publish"></span>
      </div>
    </div>

</template>
<style>
  .van-pull-refresh { min-height: 300px;}
</style>
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
