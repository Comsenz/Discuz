<template>
  <section>
    <header v-if="$route.meta.twoHeader">
      <!-- <div :class="{'fixedHead': isfixHead}">这是导航</div> -->
      <!-- 是否显示返回按钮或者使用第二套头部样式，可根据路由参数twoHeader判断 -->
      <div class="" :class="{'fixedHead': isfixHead}">
      <!-- <div class="" :class="{'fixedHead': isfixHead}"> -->
        <!--  <span class="left_btn cell_0" @click="backUrl" v-if="!$route.meta.twoHeader">返回</span> -->
        <!-- <span class="cell_1 center_content">{{$route.meta.title}}</span> -->
        <!-- <div class="hederWrap" v-if="showHeader"> -->
        <div class="hederWrap">
          <img src="../../../../../../static/images/logo.png" class="logo headLogo">
          <div class="topRight">
            <span class="icon iconfont icon-search" @click="searchJump"></span>
            <span is-link @click="showPopup" class="icon iconfont icon-Shape"></span>
          </div>
        </div>
      </div>
    </header>
    <header>
      <!-- 是否显示悬浮导航，滚动后置顶导航 -->
      <div class="" :class="{'fixedHead': isfixHead}"v-if="showHeader">
        <div class="hederWrap">
          <img src="../../../../../../static/images/logo.png" class="logo headLogo">
          <div class="topRight">
            <span class="icon iconfont icon-search" @click="searchJump"></span>
            <span is-link @click="showPopup" class="icon iconfont icon-Shape"></span>
          </div>
        </div>
      </div>
    </header>
     <!-- 是否显示返回按钮或者使用第三套头部样式，可根据路由参数threeHeader判断 -->
    <header id="headThree" v-if="$route.meta.threeHeader">
      <div class="contentHead">
        <span class="icon iconfont icon-back headBack" @click="backUrl"></span>
        <h1 class="headTit">{{$route.meta.title}}</h1>
        <!-- <span class="icon iconfont icon-Shape menuCli"></span> -->
      </div>
    </header>
    

    <!-- <div class="mask" v-if="showMask"></div>  -->
    <van-popup
      class="sidebarWrap"
      v-model="popupShow"
      position="right"
      :style="{ height: '100%' }"
   >
      <div class="sideCon">
        <div class="sideUserBox">
          <img src="../../../../../../static/images/noavatar.gif" class="userHead">
          <div class="userDet">
            <div class="userName">jdhdskhfkdshfkdsh</div>
            <div class="userPhone">183****0522</div>
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

    </van-popup>
    <div class="headerBox" v-if="$route.meta.oneHeader">
      <div class="headOpe">
        <span class="icon iconfont icon-search"></span>
        <span class="icon iconfont icon-Shape" is-link @click="showPopup"></span>
      </div>
      <img src="../../../../../../static/images/logo.png" class="logo">
      <div class="circleDet">
        <span>主题：125</span>
        <span>成员：125</span>
        <span>圈主：我是谁</span>
      </div>
      <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-if="navShow">
        <van-tabs v-model="navActi">
          <van-tab v-for="(todo, index) in todos" :title="todo.text" :key="index">

          </van-tab>
        </van-tabs>
        <!-- <div class="navBarBox">
          <ul class="navBarCon">
            <li v-for="(todo, index) in todos" v-on:click="addClass(index,$event)" v-bind:class="{ navActi:index==current}">{{ todo.text }}</li>
          </ul>
        </div> -->
      </div>
    </div>

   




  </section>
</template>
<script>
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "headerView",
  ...mSiteHeader
}

</script>
