<template>
    <div class="modify-data-box">
      <ModifyHeader title="我的资料"></ModifyHeader>
      <main class="modify-data-main content">
        <div class="modify-data-avatar">
          <input type="file" accept="image/*" @change="handleFile" class="hiddenInput"/>
          <div class="modify-data-avatar-title m-site-cell-access-bd">
            <p class="modify-data-avatar-title-img">头像</p>
          </div>
          <div class="modify-data-avatar-img">
            <img :src="headPortrait" alt="我的头像" v-if="headPortrait">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead" v-else="">
          </div>
          <i class="modify-data-avatar-right">
            <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
          </i>
        </div>
        <div class="myModifyPhone" v-show="myModifyPhone">
          <van-cell title="手机号" @click="myModify('modify-phone')" is-link :value="modifyPhone" v-if="modifyPhone"/>
          <van-cell title="手机号" @click="$router.push({path:'/bind-new-phone'})" is-link value="去绑定" v-else/>
        </div>
        <van-cell title="登录密码" @click="myModify('change-pwd')" is-link value="********"/>
        <van-cell title="钱包密码" @click="myModify('change-pay-pwd')" is-link :value="canWalletPay?'********':'设置密码'"/>
        <van-cell title="微信" is-link :value="wechatNickname" v-if="wechatNickname" @click="myModifyWechat"/>
        <van-cell title="微信" is-link value="去绑定" v-else @click="wechatBind"/>
        <div v-show="realNameShow">
          <van-cell title="实名认证" :value="realName" v-if="realName"/>
          <van-cell title="实名认证" v-else @click="$router.push({path:'/real-name'})" is-link value="认证"/>
        </div>
      </main>
    </div>
</template>
<style type="text/css" scoped>

</style>
<script>
import  '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import modifyDataCon from '../../../../controllers/m_site/myInfo/myData/modifyDataCon';
export default {
    name: "modify-data-view",
  ...modifyDataCon
}
</script>
