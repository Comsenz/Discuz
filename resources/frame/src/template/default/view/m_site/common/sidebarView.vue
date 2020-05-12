<template>
   <div class="sidebarCon">
     <div class="sideCon" @click="$router.push({path:'/home-page/'+userId})">
       <div class="sideUserBox" v-model="userInfo">
         <!-- <img src="appConfig.staticBaseUrl/static/images/noavatar.gif" class="userHead"> -->
         <div  class="side-bar-avatar-box">
           <img :src="avatarUrl" v-if="avatarUrl != '' && avatarUrl != null " class="side-user-img" >
           <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="userHead" v-else>
           <img v-if="isReal" class="icon-yirenzheng" src="/static/images/authIcon.svg" alt="实名认证">
         </div>

         <div class="userDet">
           <div class="userName" v-if="username != undefined">{{username}}</div>
           <div class="userPhone" v-if="mobile != undefined">{{mobile}}</div>
         </div>
         <span class="icon iconfont icon-right-arrow jumpJtr" style="text-align:center"></span>
       </div>
     </div>
     <div class="sideCon" v-for="(item, i) in sidebarList1" :key="i">
       <div class="sideItem" @click="sidebarUrl(item.path,item.enentType)">
       <!-- <router-link class="sideItem" :to="{path: item.path, query: item.query}" v-if="item.path"> -->
          <span class="itemTit">{{item.text}}</span>
          <i class="noticeSum" type="danger" v-show ='item.noticeSum === 0 ? false:true'>{{item.noticeSum}}</i>
          <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
       <!-- </router-link> -->
     </div>
     <div class="itemGap"></div>
     <div class="sideConList">
       <div class="sideCon" v-for="(item, j) in sidebarList2" :key="'list2'+j" >
         <div class="sideItem" @click="sidebarUrl(item.path,item.enentType)">
            <span class="itemTit">{{item.text}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
         <!-- <div class="sideItem" v-else @click="bindEvent(item.enentType)">
            <span class="itemTit">{{item.name}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div> -->
       </div>
     </div>

     <div class="itemGap"></div>
     <div class="sideConList">
       <div class="sideCon" v-for="(item, h) in sidebarList3" :key="'list3'+h">
         <div class="sideItem" @click="sidebarUrl(item.path,item.enentType)">
            <span class="itemTit">{{item.text}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
         <input type="button" hidden value="点击复制代码" />
       </div>
     </div>
    </div>
   <!-- 侧边栏 -E -->
</template>

<script>
  import '../../../defaultLess/m_site/common/common.less'
  import mSiteSidebar from '../../../controllers/m_site/common/sidebarCon';
  export default {
    name: "sidebar",
    ...mSiteSidebar
  }
</script>
