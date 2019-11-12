<template>
  <header class="header-box">
    <span @click="headerBack" class="icon iconfont header-icon icon-back" ></span>
    <span class="header-title">{{headerTitle}}</span>
    <span class="icon iconfont icon-Shape" v-show="menuIconShow"></span>
  </header>
</template>

<script>
import '../../../../../../../static/css/iconfont.css';
import '../../../../scss/m_site/common/loginSignUpHeader/loginSignUpHeader.scss';
import loginSignUpHeaderCon from '../../../../controllers/m_site/common/loginSignUpHeader/loginSignUpHeaderCon'
export default {
    name: "login-sign-up-header",
  ...loginSignUpHeaderCon
}
</script>

