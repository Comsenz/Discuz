<template>
  <section>
    <header>
      <!-- <div :class="{'fixedHead': isfixHead}">这是导航</div> -->
      <!-- 是否显示返回按钮或者使用第二套头部样式，可根据路由参数twoHeader判断 -->
      <div class="" :class="{'bg_blue':$route.meta.twoHeader,'fixedHead': isfixHead}">
        <!--  <span class="left_btn cell_0" @click="backUrl" v-if="!$route.meta.twoHeader">返回</span> -->
        <!-- <span class="cell_1 center_content">{{$route.meta.title}}</span> -->
        <div class="hederWrap" v-if="showHeader">
          <img src="../../../../../../static/images/logo.png" class="logo headLogo">
          <div class="topRight">
            <span class="icon iconfont icon-search" @click="searchJump"></span>
            <span class="icon iconfont icon-Shape" @click="bindSidebar"></span>
          </div>
        </div>
      </div>
    </header>
    <div class="mask" v-if="showMask" @click="hideSidebar"></div>
    <div class="sidebarWrap" v-if="showSidebar">
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
    </div>
    <div class="headerBox">
      <div class="headOpe">
        <span class="icon iconfont icon-search"></span>
        <span class="icon iconfont icon-Shape" @click="bindSidebar"></span>
      </div>
      <img src="../../../../../../static/images/logo.png" class="logo">
      <div class="circleDet">
        <span>主题：125</span>
        <span>成员：125</span>
        <span>圈主：我是谁</span>
      </div>
    </div>
    <div class="navBox" id="testNavBar" :class="{'fixedNavBar': isfixNav}" v-if="navShow">
      <div class="navBarBox">
        <ul class="navBarCon">
          <li v-for="(todo, index) in todos" v-on:click="addClass(index,$event)" v-bind:class="{ navActi:index==current}">{{ todo.text }}</li>
        </ul>
      </div>
    </div>
    
    


  </section>
</template>
<script>
import mSiteHeader from '../../../controllers/m_site/common/header';

import  '../../../scss/mobile/mobileIndex.scss';
export default {
  name: "headerView",
  ...mSiteHeader
}
// export default {
  // data: function() {
  //   return {
  //     isfixNav: false,
  //     isfixHead: false,
  //     isShow: false,
  //     isHeadShow: false,
  //     showHeader: false,
  //     showSidebar: false,
  //     showMask: false,
  //     sidebarList1: [
  //       {
  //         name: '我的资料',
  //         path: 'login', // 跳转路径
  //         query: { // 跳转参数
  //         index: 1
  //         },
  //           enentType: ''
  //       },
  //       {
  //         name: '我的钱包',
  //         path: 'wallent', // 跳转路径
  //         query: { // 跳转参数
  //         index: 2
  //         },
  //           enentType: ''
  //       },
  //       {
  //         name: '我的收藏',
  //         path: 'collection', // 跳转路径
  //         query: { // 跳转参数
  //         index: 3
  //         },
  //           enentType: ''
  //       }
  //     ],
  //     sidebarList2: [
  //       {
  //         name: '圈子信息',
  //         path: 'login', // 跳转路径
  //         query: { // 跳转参数
  //         index: 1
  //         },
  //           enentType: ''
  //       },
  //       {
  //         name: '圈子管理',
  //         path: 'login', // 跳转路径
  //         query: { // 跳转参数
  //           index: 2
  //         },
  //         enentType: ''
  //       },
  //       {
  //         name: '退出登录',
  //         path: '', // 跳转路径
  //         query: { // 跳转参数
  //           index: 3
  //         },
  //         enentType: 1 // 事件类型
  //       }
  //     ],
  //     sidebarList3: [
  //       {
  //         name: '邀请朋友',
  //         path: 'login', // 跳转路径
  //         query: { // 跳转参数
  //         index: 1
  //         },
  //           enentType: ''
  //       }
        
  //     ]

  //   }
  // },
  // methods: {
    
    // // 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // // 比较他们的大小来确定是否添加fixedHead样式
    // handleTabFix() {
    //     var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
    //     var offsetTop = document.querySelector('#testNavBar').offsetTop;
    //     if(scrollTop > offsetTop){
    //       // console.log('大于');
    //       this.showHeader = true;
    //       this.isfixHead = true;
    //       this.isfixNav = true;
    //       // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
    //       // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
    //     } else {
    //       // console.log('小于');
    //       this.showHeader = false;
    //       this.isfixHead = false;
    //       this.isfixNav = false;
    //       // scrollTop > offsetTop ? this.isfixHead = false : this.isfixHead = true;
    //       // scrollTop < offsetTop ? this.isfixNav = false : this.isfixNav = true
    //     };    
        
    //   },
    //   searchJump () {

    //   },
    //   backUrl () {
    //     // 返回上一级
    //     window.history.go(-1)
    //   },
    //   bindSidebar () {
    //     // 是否显示侧边栏
    //     this.showSidebar = !this.showSidebar;
    //     this.showMask =  !this.showMask;
    //   },
    //   hideSidebar(){
    //     this.showSidebar = false;
    //     this.showMask =  false;
    //   },
    //   bindEvent (typeName) {
    //     if (typeName == 1) {
    //       this.LogOut()
    //     }
    //   },
    //   LogOut () {
    //     console.log('测试');
    //   }

  // },

  // mounted: function() {
  //   // this.getVote();
  //   window.addEventListener('scroll', this.handleTabFix, true);
  // },
  // beforeRouteLeave (to, from, next) {
  //    window.removeEventListener('scroll', this.handleTabFix, true)
  //    next()
  // }
// }
// </script>


