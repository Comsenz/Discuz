<template>
	<div>
		后台首页
		<button @click="buttonClick">+1</button>
    <button @click="login">登录</button>
    <p>{{this.$store.state.site.num}}</p>
    <!--<p>{{this.$store.state.login.loginState}}</p>-->
    <p>{{this.loginState}}</p>
    <p>{{this.countPlusLocalState}}</p>

    <a href="javascript:;" class="weui-btn weui-btn_primary">页面主操作</a>
	</div>
</template>

<script>
import indexCon from 'indexCon';
export default {
	name: "adminIndex",
  ...indexCon
}
</script>
