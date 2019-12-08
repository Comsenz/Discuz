<template>
    <div class="login-user-box">
      <comHeader></comHeader>
      <main class="login-user-box-main">
        <img :src="appConfig.staticBaseUrl+'/images/logo.png'" class="logo weLogo">
        <van-button type="primary" @click="getUrlCode" loading-text="授权中..." class="weAuthorizeBtn">去微信授权</van-button>

        <div class="login-user-method">
          <div class="login-user-method-box">
            <van-divider>其他登录方式</van-divider>
          </div>

          <div class="login-user-method-icon">
            <div class="login-user-method-icon-box">

              <i @click="loginPhoneClick" class="login-user-method-icon-ring iconfont">
                <span class="icon iconfont icon-shouji" style="color:rgba(136, 136, 136, 1);"></span>
              </i>

              <i @click="loginWxClick" class="login-user-method-icon-ring iconfont">
                <span class="icon iconfont icon-weixin" style="color:rgba(136, 136, 136, 1);"></span>
              </i>
            </div>
          </div>
        </div>
      </main>
    </div>


</template>

<script>
import wechatCon from '../../../controllers/m_site/common/wechatCon';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import '../../../scss/m_site/login/loginSignUpModule.scss';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "wechat",
    components:{
    	comHeader
    },
  ...wechatCon
}
</script>
