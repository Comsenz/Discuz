<template>
	<div>
		后台首页
		<el-button @click="buttonClick">+1</el-button>
    <el-button @click="login">登录</el-button>
    <p>{{this.$store.state.site.num}}</p>
    <!--<p>{{this.$store.state.login.loginState}}</p>-->
    <p>{{this.loginState}}</p>
    <p>{{this.countPlusLocalState}}</p>
	</div>
</template>

<script>
import indexCon from 'indexCon';
export default {
	name: "adminIndex",
  ...indexCon
}
</script>
