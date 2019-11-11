<template>
  <el-container class="index-container-box">
    <el-header height="80px" class="index-header">
      <h1 class="index-header__icon">
        <a href="/admin/index">
          <img src="../../../../static/images/admin-logo.png"  alt="Logo">
        </a>
      </h1>

      <div class="index-header__nav">
        <ul class="menu-demo">
          <!--is-active-->
          <li class="menu-item" @click="menuClick(item.name)" :class="item.name === '首页'?'is-active':''" v-for="(item,index) in navList" :key="index">{{item.name}}</li>

        </ul>

      </div>

      <div class="index-header__info-menu">
          <span>您好，admin [退出]</span>
          <span class="site-home">站点首页</span>
      </div>

    </el-header>

    <el-container class="index-main-con">

      <el-aside class="index-main-con__side" width="256px">
        <div class="index-main-con__side-title">
          <span>{{sideTitle}}</span>
        </div>

        <div class="index-main-con__side-list">

          <ul class="index-side-ul">
            <li class="index-side-li is-active">
              <span></span>
              <span>管理中心首页</span>
            </li>

            <li class="index-side-li">
              <span></span>
              <span>站点信息</span>
            </li>

            <li class="index-side-li">
              <span></span>
              <span>注册设置</span>
            </li>

            <li class="index-side-li">
              <span></span>
              <span>登录设置</span>
            </li>
          </ul>

        </div>

        <div class="index-main-con__side-footer">
          <p>Powered by Discuz! Q</p>
        </div>
      </el-aside>

      <el-main class="index-main-con__main">

        <div class="index-main-con__main-title">
          <h1>{{indexTitle}}</h1>
        </div>

        <div class="router-con">
          <router-view></router-view>
        </div>

      </el-main>
    </el-container>

  </el-container>
</template>

<script>
import '../../scss/indexView.scss';
import IndexCon from '../../controllers/site/IndexCon';
export default {
	name: "adminIndex",
  ...IndexCon
}
</script>
