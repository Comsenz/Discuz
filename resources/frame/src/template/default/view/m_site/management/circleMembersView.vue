<!--移动端首页模板-->

<template>
    <div>
      <div class="foueHeadBox">
        <div class="fourHeader" headFixed="true">
            <span class="icon iconfont icon-back headBack" ></span>
            <h1 class="headTit">{{$route.meta.title}}</h1>
        </div>
        <div class="serBox" @click="serToggle" v-show="serHide">
          <input type="text" name="" placeholder="搜索" class="serInp">
          <i class="icon iconfont icon-search"></i>
        </div>
        <form action="/">
          <van-search
            v-model="searchVal"
            v-show="serShow"
            ref="serInp"
            placeholder="搜索用户和主题"
            background="#f8f8f8"
            show-action
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
      <div class="searchRes">
        <div class="resUser" v-for="(item, index) in searchUserList" :key="index">
          <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead">
          <div class="resUserDet">
            <!-- <span class="resUserName">多少分接<i>你</i>口的是否健康的首付款觉得第三方第三方是的是的是的所舒服的</span> -->
            <!-- <span class="resUserName">{{item.username().slice(0,item.username().indexOf(searchVal))}}<i>{{searchVal}}</i>{{item.username().substr(item.username().indexOf(searchVal) + 1)}}</span> -->
            <span class="resUserName" v-html="item._data.username.replace(searchVal,'<i>'+searchVal+'</i>')"></span>
            <span class="userRole" v-for="(role,index) in item._data.groups">{{role.name}}</span>
          </div>
        </div>
      </div>
  </van-pull-refresh>    
  </van-list>
    </div>
</template>
<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
import '../../../less/m_site/myInfo/myInfo.less';
import mSiteCircleMembersCon from '../../../controllers/m_site/management/circleMembersCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	// Header
    },
    // ...mSiteHeader,
    ...mSiteCircleMembersCon
}



</script>
