<template>
  <div class="">
    <header class="header-box" id="comHeader">
      <span @click="headerBack" class="icon iconfont header-icon icon-back" ></span>
      <span class="header-title">{{headerTitle}}</span>
      <span class="icon iconfont icon-Shape comHeadMenu" v-show="menuIconShow" @click="showPopup()"></span>
    </header>
    <van-popup
       class="sidebarWrap"
       v-model="popupShow"
       position="right"
       :style="{ height: '100%' }"
    >
       <sidebar></sidebar>
     </van-popup>
     <!-- 侧边栏 -E -->
  </div>

</template>

<script>
import '../../../../../../../static/css/iconfont.css';
// import '../../../../scss/m_site/common/loginSignUpHeader/loginSignUpHeader.scss';
import '../../../../defaultLess/m_site/common/common.less';
import Sidebar from '../../../m_site/common/sidebarView';
import loginSignUpHeaderCon from '../../../../controllers/m_site/common/loginSignUpHeader/loginSignUpHeaderCon'
export default {
    name: "login-sign-up-header",
    components:{
    	Sidebar
    },
  ...loginSignUpHeaderCon
}
</script>
