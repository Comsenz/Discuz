<template>
<section>
<header>
  <!-- 是否显示返回按钮或者使用第二套头部样式，可根据路由参数twoHeader判断 -->
  <div class="heder-wrap flex" :class="{'bg_blue':$route.meta.twoHeader}">
    <span class="left_btn cell_0" @click="backUrl" v-if="!$route.meta.twoHeader">返回</span>
    <span class="cell_1 center_content">{{$route.meta.title}}</span>
    <span class="right_btn cell_0" @click="bindSidebar">菜单</span>
  </div>
</header>
<div class="sidebar_wrap" v-if="showSidebar">
  <p v-for="(item, i) in sidebarList" :key="i">
    <router-link class="sidebar_item" :to="{path: item.path, query: item.query}" v-if="item.path">{{item.name}}</router-link>
    <span class="sidebar_item" v-else @click="bindEvent(item.enentType)">{{item.name}}</span>
  </p>
</div>
</section>
</template>
<script>
export default {
  data () {
    return {
      showSidebar: false,
      sidebarList: [
        {
          name: '我的资料',
          path: 'login', // 跳转路径
          query: { // 跳转参数
            index: 1
          },
          enentType: ''
        },
        {
          name: '退出登录',
          path: '', // 跳转路径
          query: { // 跳转参数
            index: 1
          },
          enentType: 1 // 事件类型
        }
      ]
    }
  },
  methods: {
    backUrl () {
      // 返回上一级
      window.history.go(-1)
    },
    bindSidebar () {
      // 是否显示侧边栏
      this.showSidebar = !this.showSidebar
    },
    bindEvent (typeName) {
      if (typeName == 1) {
        this.LogOut()
      }
    },
    LogOut () {
      console.log('测试')
    }
  }
}
</script>

<style lang="scss">
.flex, .flex *, .flex:after, .flex:before {
  box-sizing: border-box;
  display: flex;
  -webkit-flex-wrap: wrap;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}
.flex > .cell_0 {
  display: block;
  position: relative;
}
.flex > .cell_1 {
  -webkit-box-flex: 1;
  -moz-box-flex: 1;
  -webkit-flex: 1;
  -ms-flex: 1;
  flex: 1;
  display: block;
  position: relative;
  -webkit-flex-basis: 0;
  -ms-flex-preferred-size: 0;
  flex-basis: 0;
}
.heder-wrap{
  background: #f5f5f5;
  padding: 0.5rem 0;
}
.center_content{
  text-align: center;
}
.left_btn, .right_btn{
  width: 4rem;
  text-align: center;
}
// 侧边栏
.sidebar_wrap{
  background: #eee1e1;
  width: 50%;
  position: absolute;
  right: 0;
}
.bg_blue{
  background: #00f;
}
.sidebar_item{
  line-height: 2;
  text-align: center;
  display: block;
}
</style>
