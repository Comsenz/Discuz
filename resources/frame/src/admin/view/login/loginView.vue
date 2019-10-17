<template>
    <div>
      <p>登录</p>
      <el-button @click="black">登录成功</el-button>
      <el-button @click="user">用户管理</el-button>
      <p>{{this.$store.state.login.loginState}}</p>
    </div>
</template>

<script>
import loginCon from 'loginCon';
export default {
  name: "login-view",
  ...loginCon
}
</script>
