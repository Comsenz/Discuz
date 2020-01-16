<template>
  <el-container class="index-container-box">
    <el-header height="80px" class="index-header">
      <h1 class="index-header__icon">
        <a href="/admin/home">
          <img src="../../../../static/images/admin-logo.png"  alt="Logo">
        </a>
      </h1>

      <div class="index-header__nav">
        <ul class="menu-demo">
          <li class="menu-item"
              @click="menuClick(item)"
              :class="navSelect === item.name?'is-active':''"
              v-for="(item,index) in navList"
              :key="index">
            {{item.title}}
          </li>
        </ul>
      </div>

      <div class="index-header__info-menu">
        <span>您好，{{userName}}</span>
        <span @click="quitClick">&nbsp;[退出]</span>
        <span class="site-home" ><a href="https://2020.comsenz-service.com/" target="view_window">站点首页</a></span>
      </div>

    </el-header>

    <el-container class="index-main-con">

      <el-aside class="index-main-con__side" width="256px">
        <div class="index-main-con__side-title">
          <span>{{sideTitle}}</span>
        </div>

        <div class="index-main-con__side-list">

          <ul class="index-side-ul">
            <li v-for="item in sideList"
                class="index-side-li"
                :class="sideSelect === item.name?'is-active':''"
                @click="sideClick(item)">
              <span class="iconfont" :class="item.icon"></span>
              <span>{{item.title}}</span>
            </li>

          </ul>

        </div>

        <div class="index-main-con__side-footer">
          <p>Powered by Discuz! Q</p>
        </div>
      </el-aside>

      <el-main ref="indexMainCon" class="index-main-con__main">

        <div class="index-main-con__main-title">
          <h1>{{indexTitle}}</h1>
          <div class="index-main-con__main-title__class">
            <i v-if="sideSubmenu.length > 0"></i>
            <span v-for="(item,index) in sideSubmenu" @click="sideSubmenuClick(item.title)" :class="item.title === sideSubmenuSelect?'is-active':''" :key="index">{{item.title}}</span>
          </div>
        </div>

        <div class="router-con">
          <router-view></router-view>
        </div>

      </el-main>
    </el-container>

  </el-container>
</template>

<script>
import '../../scss/site/module/indexView.scss';
import IndexCon from '../../controllers/site/IndexCon';
export default {
	name: "adminIndex",
  ...IndexCon
}
</script>
