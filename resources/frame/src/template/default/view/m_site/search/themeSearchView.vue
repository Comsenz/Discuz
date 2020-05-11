<!--移动端首页模板-->

<template>
    <div>
      <div class="foueHeadBox">
        <div class="fourHeader" headFixed="true">
            <span class="icon iconfont icon-back headBack" @click="headerBack" ></span>
            <h1 class="headTit">{{$route.meta.title}}</h1>
        </div>
        <!-- <div class="serBox" @click="serToggle" v-show="serHide">
          <input type="text" name="" placeholder="搜索" class="serInp" v-model="inputSearchVal">
          <i class="icon iconfont icon-search"></i>
        </div> -->
        <form action="/">
          <van-search
            v-model="searchVal"
            v-show="serShow"
            ref="serInp"
            placeholder="搜索主题"
            background="#f8f8f8"
            @input="onSearch"
            @cancel="onCancel"
            class="searchCon"
          />
        </form>
      </div>
    <van-list
    v-model="loading"
    :finished="finished"
    finished-text="没有更多了"
    :offset="offset"
    @load="onLoad"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <ThemeDet :themeList="searchThemeList"></ThemeDet>
      <!-- <div class="themeRes" v-for="(item, index) in searchThemeList" :key="index">
        <div class="postTop">
          <div class="postPer">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
            <div class="perDet">
              <div class="perName">{{item.user._data.username}}</div>
              <div class="postTime">{{$moment(item._data.createdAt).fromNow()}}</div>
            </div>
          </div>
        </div>
        <div class="postContent">
          <a href="javascript:;">{{item.firstPost._data &&item.firstPost._data.content}}</a>
        </div>
      </div> -->
  </van-pull-refresh>
  </van-list>
    </div>
</template>
<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
import themeSearchCon from '../../../controllers/m_site/search/themeSearchCon';
import ThemeDet from '../common/themeDetView'
// import  '../../../scss/m_site/mobileIndex.scss';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
import  '../../../defaultLess/m_site/modules/myInfo.less';
import  '../../../defaultLess/m_site/modules/manageCircle.less';
export default {
    name: "themeSearchView",
    components:{
        ThemeDet
    },
    ...themeSearchCon
}



</script>
