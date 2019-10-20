<template>
  <header class="header-box">
    <span @click="headerBack" class="icon iconfont header-icon" >&#xe605;</span>
    <span class="header-title">{{headerTitle}}</span>
  </header>
</template>

<script>
import '../../../../../../../static/css/iconfont.css';
import '../../../../scss/mobile/common/loginSignUpHeader/loginSignUpHeader.scss';
import loginSignUpHeaderCon from '../../../../controllers/m_site/common/loginSignUpHeader/loginSignUpHeaderCon'
export default {
    name: "login-sign-up-header",
  ...loginSignUpHeaderCon
}
</script>

