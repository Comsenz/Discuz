<template>
    <div class="pay-the-fee-box">
      <PayHeader></PayHeader>

      <main class="pay-the-fee-main">
        <div class="pay-the-fee-titel">
          <h2>支付费用</h2>
        </div>
        <van-button type="primary" @click="payClick">立即付费,获得权限</van-button>
        <p class="pay-the-fee-title-footer">￥{{sitePrice}} / {{siteExpire}}</p>
      </main>

      <div class="pay-the-fee-permission">
        <div class="pay-the-fee-per-title">
          <h3>作为成员，您将获得以下权限</h3>
        </div>
        <div class="pay-the-fee-per-list">
          <div class="pay-the-fee-per-name">帖子操作</div>
          <div class="" v-for="(limit,index) in limitList.permission">
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewThreadList'">查看主题列表</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewThreads'">查看主题</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createThread'">发表主题</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.reply'">回复主题</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.0'">上传附件</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.create.1'">上传图片</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.0'">查看附件</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.view.1'">查看图片</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewUserList'">站点会员列表</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'attachment.delete'">删除附件</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'cash.create'">cash.create</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'order.create'">order.create</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.deletePosts'">删除回复</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.favorite'">帖子收藏</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.likePosts'">帖子点赞</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.view'">user.view</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'viewSiteInfo'">站点信息</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'user.edit'">编辑用户状态（例如：禁用）</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'group.edit'">编辑用户组</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'createInvite'">管理-邀请加入</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.hide'">批量删除帖子</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.editPosts'">编辑</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.essence'">加精</p>
            <p class="powerChi" v-if="limit._data.permission && limit._data.permission == 'thread.sticky'">置顶</p>
          </div>
        </div>
        <div class="pay-the-fee-per-list-footer">
          <p @click="leapFrogClick">跳过，进入首页</p>
        </div>
      </div>

      <van-popup
        v-model="qrcodeShow"
        round
        close-icon-position="top-right"
        closeable
        class="qrCodeBox"
        get-container="body">
        <span class="popupTit">立即支付</span>
        <div class="payNum">￥<span>{{amountNum}}</span></div>
        <div class="payType">
          <span class="typeLeft">支付方式</span>
          <span class="typeRight"><i class="icon iconfont icon-wepay"></i>微信支付</span>
        </div>
        <img :src="codeUrl" alt="微信支付二维码" class="qrCode">
        <p class="payTip">微信识别二维码支付</p>
      </van-popup>
    </div>
</template>

<script>
import '../../../scss/m_site/login/loginSignUpModule.scss';
import payTheFeeCon from '../../../controllers/m_site/login/payTheFeeCon';
export default {
    name: "pay-the-fee-view",
  ...payTheFeeCon
}
</script>

