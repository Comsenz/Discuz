<template>
    <div class="searchBox">
      <form action="/">
        <van-search
          v-model="searchVal"
          placeholder="搜索用户和主题"
          background="#f8f8f8"
          show-action
          @input="onSearch"
          @cancel="onCancel"
          class="searchCon"
        />
      </form>
      <div class="searchRes" v-show="searchUserList.length > 0">
        <h2 class="resultTit">用户</h2>
        <div class="resUser" v-for="(item, index) in searchUserList" :key="index"  @click="jumpPerDet(item._data.id)">
          <img v-if="item._data.avatarUrl" :src="item._data.avatarUrl" class="resUserHead"  @click="jumpPerDet(item._data.id)">
          <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead"  @click="jumpPerDet(item._data.id)">
          <div class="resUserDet">
            <!-- <span class="resUserName">多少分接<i>你</i>口的是否健康的首付款觉得第三方第三方是的是的是的所舒服的</span> -->
            <!-- <span class="resUserName">{{item.username().slice(0,item.username().indexOf(searchVal))}}<i>{{searchVal}}</i>{{item.username().substr(item.username().indexOf(searchVal) + 1)}}</span> -->
             <span class="resUserName" v-html="item._data.username.replace(searchVal,'<i>'+searchVal+'</i>')" ></span>
            <span class="userRole">{{item.groups[0] && item.groups[0]._data.name}}</span>
          </div>
        </div>
        <div class="searchMore" v-show='userLoadMoreStatus' @click="handleLoadMoreUser">
          <i class="icon iconfont icon-search"></i>
          搜索更多用户
        </div>
      </div>
      <div class="gap" v-show='searchThemeList.length > 0'></div>
      <div class="searchRes" v-show='searchThemeList.length > 0'>
        <h2 class="resultTit">主题</h2>
        <div class="themeRes" v-for="(item, index) in searchThemeList" :key="index">
          <div class="postTop">
            <div class="postPer">
              <img v-if="item.user._data.avatarUrl" :src="item.user._data.avatarUrl" class="postHead" @click="jumpPerDet(item.user._data.id)">
              <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead"  @click="jumpPerDet(item.user._data.id)">
              <div class="perDet">
                <div class="perName">{{item.user._data.username}}</div>
                <div class="postTime">{{$moment(item._data.createdAt).fromNow()}}</div>
              </div>
            </div>
          </div>
          <div class="postContent"  @click="jumpDetails(item._data.id)">
            <a href="javascript:;" v-html="item.firstPost._data &&item.firstPost._data.contentHtml"></a>
          </div>
        </div>
        <div class="searchMore" v-show="themeLoadMoreStatus" @click="handleLoadMoreTheme">
          <i class="icon iconfont icon-search"></i>
          搜索更多主题
        </div>
      </div>
      <!-- <div v-show="searchThemeList.length === 0 && searchUserList.length === 0 && !firstComeIn">
        暂无用户
      </div> -->
    </div>
</template>
<style type="text/css" scoped>
  .searchBox { height: 100%; background: #EEEFEF; }
  .searchCon { box-shadow: 0px 1px rgba(0,0,0,0.02); margin-bottom: 1px;}
</style>
<script>
// import  '../../../scss/m_site/mobileIndex.scss';

import searchCon from '../../../controllers/m_site/search/searchCon';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
import  '../../../defaultLess/m_site/modules/search.less';
export default {
    name: "searchView",
  ...searchCon
}
</script>
