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
	    <Header :searchIconShow="true" :perDetShow="true" :logoShow="true" :menuIconShow="true" navShow="true" headFixed="true" @click="headerBack"></Header>
      <div class="gap"></div>
      <div class="themeTitBox">
        <span class="themeTit">全部主题</span>
        <div class="screen" @click="bindScreen">
          <span>筛选</span>
          <span class="icon iconfont icon-down-menu jtGrayB"></span>
          <!-- <div class="themeList" v-if="showScreen">
            <a href="javascript:;">全部主题</a>
            <a href="javascript:;">精华主题</a>
          </div> -->
        </div>
      </div>

	    <div class="memberCheckList">
        <ThemeDet :themeList="themeListCon" :isTopShow="true" :isMoreShow="true" :ischeckShow="true" v-on:deleteAll="deleteAllClick" ></ThemeDet>
        <div class="gap"></div>
	    </div>
	    <!-- <div class="manageFootFixed choFixed">
          <a href="javascript:;" @click="checkAll">全选</a>
			<a href="javascript:;" @click="signOutDele">取消全选</a>
			<button class="checkSubmit">删除选中</button>
		</div> -->
      </van-pull-refresh>
    </van-list>
  </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import '../../../vantJS/m_site/tabs/Title.js';
import mSiteDeleteCon from '../../../controllers/m_site/management/deleteCon';
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
    ...mSiteDeleteCon
}

</script>
