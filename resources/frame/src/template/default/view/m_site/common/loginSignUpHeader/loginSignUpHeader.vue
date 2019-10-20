<template>
  <header class="header-box">
    <svg
      @click="headerBack"
      t="1571393758601"
      class="icon header-icon"
      viewBox="0 0 1024 1024"
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      p-id="2473"
      width="9"
      height="16">
      <path
        d="M796.476945 950.89849c16.760011 16.703711 16.760011 43.832142 0 60.537899-16.676072 16.751821-43.809621 16.751821-60.490812 0L226.537293 544.63739c-8.900525-8.950683-12.714597-20.789046-12.134194-32.452368-0.580402-11.670487 3.232646-23.532394 12.134194-32.458509l509.449864-466.799c16.681191-16.751821 43.81474-16.751821 60.490812 0 16.760011 16.721112 16.760011 43.832142 0 60.537899L317.818085 512.183999 796.476945 950.89849 796.476945 950.89849zM796.476945 950.89849"
        p-id="2474">
      </path>
    </svg>
    <span class="header-title">{{headerTitle}}</span>
  </header>
</template>

<script>
import '../../../../scss/mobile/common/loginSignUpHeader/loginSignUpHeader.scss';
import loginSignUpHeaderCon from '../../../../controllers/m_site/common/loginSignUpHeader/loginSignUpHeaderCon'
export default {
    name: "login-sign-up-header",
  ...loginSignUpHeaderCon
}
</script>

