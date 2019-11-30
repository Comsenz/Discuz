<template>
   <div class="sidebarCon">
     <div class="sideCon">
       <div class="sideUserBox">
         <!-- <img src="../../../../../../static/images/noavatar.gif" class="userHead"> -->
         <img src="userAvatarUrl" alt="" class="userHead" v-if="userAvatarUrl == '' && userAvatarUrl == null">
         <img src="../../../../../../static/images/noavatar.gif" class="userHead" v-else="">
         <div class="userDet">
           <div class="userName">{{userName}}</div>
           <div class="userPhone">{{userMobile}}</div>
         </div>
         <span class="icon iconfont icon-right-arrow jumpJtr"></span>
       </div>
     </div>
     <div class="sideCon" v-for="(item, i) in sidebarList1" :key="i">
       <div class="sideItem" :to="{path: item.path, query: item.query}" v-if="item.path">
          <span class="itemTit">{{item.name}}</span>
          <span class="icon iconfont icon-right-arrow jumpJtr"></span>
       </div>
     </div>
     <div class="itemGap"></div>
     <div class="sideConList">
       <div class="sideCon" v-for="(item, i) in sidebarList2" :key="'list2'+i">
         <div class="sideItem" :to="{path: item.path, query: item.query}" v-if="item.path">
            <span class="itemTit">{{item.name}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
         <div class="sideItem" v-else @click="bindEvent(item.enentType)">
            <span class="itemTit">{{item.name}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
       </div>
     </div>

     <div class="itemGap"></div>
     <div class="sideConList">
       <div class="sideCon" v-for="(item, i) in sidebarList3" :key="'list3'+i">
         <div class="sideItem" :to="{path: item.path, query: item.query}" v-if="item.path">
            <span class="itemTit">{{item.name}}</span>
            <span class="icon iconfont icon-right-arrow jumpJtr"></span>
         </div>
       </div>
     </div>
    </div>
   <!-- 侧边栏 -E -->
</template>

<script>
  import mSiteSidebar from '../../../controllers/m_site/common/sidebarCon';
  export default {
    name: "sidebar",
    ...mSiteSidebar
  }
</script>
