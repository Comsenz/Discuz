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
        <span>您好，admin</span>
        <span @click="$router.push({path:'/admin/login'})">&nbsp;[退出]</span>
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
