<template>
  <div class="modify-data-box">
    <ModifyHeader title="我的资料"></ModifyHeader>
    <main class="modify-data-main content">
      <div class="modify-data-avatar">
        <input type="file" accept="image/*" @change="handleFile" class="hiddenInput" />
        <div class="modify-data-avatar-title m-site-cell-access-bd">
          <p class="modify-data-avatar-title-img">头像</p>
        </div>
        <div class="modify-data-avatar-img">
          <div v-if="!updataLoading" class="modify-data-avatar-box">
            <img :src="headPortrait" alt="我的头像" v-if="headPortrait" />
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead" v-else />
            <img
              v-if="isReal"
              class="icon-yirenzheng"
              src="/static/images/authIcon.svg"
              alt="实名认证"
            />
          </div>
          <van-loading v-if="updataLoading" type="spinner" />
        </div>
        <i class="modify-data-avatar-right">
          <span class="icon iconfont icon-right m-site-cell-access-ft-icon" style="color: #e5e5e5;"></span>
        </i>
      </div>
      <van-cell title="用户名" @click="myModify('modify-username')" is-link :value="username" />
      <div class="myModifyPhone" v-show="myModifyPhone">
        <van-cell
          title="手机号"
          @click="myModify('modify-phone')"
          is-link
          :value="modifyPhone"
          v-if="modifyPhone"
        />
        <van-cell
          title="手机号"
          @click="$router.push({path:'/bind-new-phone'})"
          is-link
          value="去绑定"
          v-else
        />
      </div>
      <van-cell title="登录密码" @click="myModify('change-pwd')" is-link :value="hasPassword ? '********' : '设置密码'" />
      <van-cell
        title="钱包密码"
        @click="myModify('change-pay-pwd')"
        is-link
        :value="canWalletPay?'********':'设置密码'"
      />
      <van-cell
        title="微信已绑定"
        is-link
        value="点击解绑并退出登录"
        v-if="wechatNickname"
        @click="myModifyWechat"
      />
      <van-cell title="微信未绑定" is-link value="点击立即绑定" v-else @click="wechatBind" />
      <div v-show="realNameShow">
        <van-cell title="实名认证" :value="realName" v-if="realName" />
        <van-cell
          title="实名认证"
          v-else
          @click="$router.push({path:'/real-name'})"
          is-link
          value="认证"
        />
      </div>
    </main>
  </div>
</template>
<style type="text/css" scoped>
</style>
<script>
import "../../../../defaultLess/m_site/common/common.less";
import "../../../../defaultLess/m_site/modules/myInfo.less";
import modifyDataCon from "../../../../controllers/m_site/myInfo/myData/modifyDataCon";
export default {
  name: "modify-data-view",
  ...modifyDataCon
};
</script>
