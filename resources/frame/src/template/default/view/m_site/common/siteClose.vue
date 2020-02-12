<template>
    <div class="siteCom">
        <van-icon class="logo" name="warning" color="#c33" size="64"/>
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
    text-align: center;
    width: 64px;
    height:64px;
}
.siteClose{
    width:100%;
    text-align: center;
    margin-top:43px;
    font-size:19px;
    color:black;
}
.siteReason{
    width:65%;
    text-align:center;
    margin-top:10px;
    font-size: 15px;
    color:#000;
    opacity: 0.5;
}
.login{
     width:100%;
     text-align: center;
     margin-top:24px;
}
</style>
