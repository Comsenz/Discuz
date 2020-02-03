<template>
    <div class="modify-data-box">
      <ModifyHeader title="我的资料"></ModifyHeader>
      <main class="modify-data-main content">
        <div class="modify-data-avatar m-site-cell-access">
          <div class="modify-data-avatar-title m-site-cell-access-bd">
            <p>头像</p>
          </div>
          <div class="modify-data-avatar-img">
            <input type="file" accept="image/*" @change="handleFile" class="hiddenInput"/>
             <img :src="headPortrait" alt="我的头像" v-if="headPortrait">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead" v-else="">
            <i>
              <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
            </i>
          </div>
        </div>
        <van-cell title="手机号" @click="myModify('modify-phone')" is-link :value="modifyPhone" v-if="modifyPhone"/>
        <van-cell title="手机号" @click="$router.push({path:'/bind-new-phone'})" is-link value="去绑定" v-else />
        <van-cell title="密码" @click="myModify('change-pwd')" is-link value="********" />
        <van-cell title="微信" is-link :value="wechatNickname" v-if="wechatNickname"  @click="myModifyWechat" />
        <van-cell title="微信" is-link value="去绑定" v-else  @click="wechatBind" />
      </main>

    </div>
</template>
<style type="text/css" scoped>
 .modify-data-avatar-img{position: relative;display: inline-block;}
  .hiddenInput {position: absolute;left: -7.73333rem;opacity:0;width:143rem;height:3rem;}
  /* .searchCon { box-shadow: 0px 1px rgba(0,0,0,0.02); margin-bottom: 1px;} */
</style>
<script>
// import '../../../scss/m_site/myInfo/myInfo.scss';

import '../../../defaultLess/m_site/modules/myInfo.less';
import modifyDataCon from '../../../controllers/m_site/myInfo/modifyDataCon';
export default {
    name: "modify-data-view",
  ...modifyDataCon
}
</script>
