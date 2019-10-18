<template>
    <div>
      <p>登录</p>
      <button @click="black">登录成功</button>
      <button @click="user">用户管理</button>
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
