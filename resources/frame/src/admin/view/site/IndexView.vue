<template>
  <el-container>
    <el-header height="80px">
      头部
    </el-header>

    <el-container>
      <el-aside width="256px">菜单</el-aside>
      <el-main>
        内容
        <router-view></router-view>
      </el-main>
    </el-container>

  </el-container>

	<!--<div class="ad-index-box">-->
    <!--<header class="ad-index-header">-->

    <!--</header>-->

    <!--<main>-->
      <!--<div>菜单</div>-->
      <!--<div>内容</div>-->
    <!--</main>-->

	<!--</div>-->

</template>

<script>
import '../../scss/indexView.scss';
import indexCon from 'indexCon';
export default {
	name: "adminIndex",
  ...indexCon
}
</script>
