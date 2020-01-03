<template>
    <div class="siteCom">
        <img class="logo" :src="imgLogo"  alt="Logo">
        <div class="siteClose">站点已关闭</div>
        <div class="siteReason">{{closeReason}}</div>
        <div class="login">
          <van-button type="primary" @click="loginClick">登录</van-button>
        </div>
    </div>
</template>
<script>
  import sideClose from '../../../controllers/m_site/common/siteClose';
  export default {
    name: "sideClose",
  ...sideClose
}
</script>
<style lang="less">
.siteCom{
    width:100%;
    height:100%;
}
.logo{
    display:block;
    margin: 40% auto 0;
    height:30px;

}
.siteClose{
    width:100%;
    text-align: center;
    padding-top:40px;
    font-size:19px;
    color:black; 
    // margin-top:10px;
}
.siteReason{
    width:100%;
    text-align: center;
    line-height: 40px;
    font-size: 15px;
    color:#ccc
}
.login{
     width:100%;
     margin-top:10px;
     text-align: center;
}
</style>