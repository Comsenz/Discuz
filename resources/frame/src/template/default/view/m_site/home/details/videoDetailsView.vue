<template>
  <div v-model="themeCon">
    <div class="postTop">
      <div class="postPer">
        <div class="avatar-box">
          <img
            :src="themeCon.user._data.avatarUrl"
            @click="jumpPerDet(themeCon.user._data.id)"
            class="user-img"
            v-if="themeCon.user && themeCon.user._data.avatarUrl"
          />
          <img
            :src="appConfig.staticBaseUrl + '/images/noavatar.gif'"
            class="postHead"
            v-else
            @click="jumpPerDet(themeCon.user._data.id)"
          />
          <img
            v-if="themeCon.user._data.isReal"
            class="icon-yirenzheng"
            src="/static/images/authIcon.svg"
            alt="实名认证"
          />
        </div>

        <div class="perDet">
          <div
            class="perName"
            v-if="themeCon.user"
            @click="jumpPerDet(themeCon.user._data.id)"
          >
            {{ themeCon.user._data.username }}
            <span class="groupsName" v-if="themeCon.user._data.showGroups"
              >({{
                themeCon.user.groups
                  ? themeCon.user.groups[0]._data.name
                  : "无用户组"
              }})</span
            >
          </div>
          <div class="perName" v-else>该用户已被删除</div>
          <div class="postTime">
            {{ $dayjs(themeCon._data.createdAt).format("YYYY-MM-DD HH:mm") }}
          </div>
        </div>
      </div>
      <div class="postOpera">
        <span
          class="icon iconfont icon-top"
          v-if="themeCon._data.isSticky"
        ></span>
      </div>
    </div>
    <div
      class="videoContentText"
      v-html="themeCon.firstPost._data.contentHtml"
    ></div>
    <div
      class="videoBox"
      v-if="themeCon._data.price > 0 && !themeCon._data.paid"
    >
      <!-- <img
        class="videoCover"
        v-if="themeCon.threadVideo._data.file_id == '' || themeCon.threadVideo._data.file_id == null"
        :src="themeCon.threadVideo._data.cover_url"
        alt
      /> -->
      <div
        class="postImgList"
        v-if="
          themeCon.threadVideo._data.file_id == '' ||
            themeCon.threadVideo._data.file_id == null
        "
      >
        <van-image
          lazy-load
          class="videoCover"
          :src="themeCon.threadVideo._data.cover_url"
          fit="contain"
        />
      </div>
    </div>
    <div class="videoBox" v-else>
      <img
        v-if="themeCon.threadVideo._data.status == 0"
        :src="appConfig.staticBaseUrl + '/images/transcoding.png'"
        alt
        class="transcodingCover"
      />
      <div
        style="text-align: center"
        class="videoContent"
        v-if="
          themeCon.threadVideo._data.file_id != '' &&
            themeCon.threadVideo._data.file_id != null &&
            themeCon.threadVideo._data.status == 1
        "
      >
        <div v-show="themeCon._data.paid" style="display: inline-block">
          <!--<video
            :id="tcPlayerId"
            preload="auto"
            width="100%"
            playsinline
            webkit-playsinline
            x5-video-player-type="h5-page"
          ></video>-->
          <video
            preload="auto"
            controls
            width="100%"
            :src="themeCon.threadVideo._data.media_url"
          ></video>
        </div>
      </div>
    </div>
    <div
      class="payTipBox"
      v-if="themeCon._data.price > 0 && !themeCon._data.paid"
    >
      <p class="tipPrice">
        本内容需向作者支付&nbsp;
        <span>{{ themeCon._data.price }}</span
        >&nbsp;元&nbsp;才能浏览
      </p>
      <a href="javascript:;" @click="buyTheme" class="buyBtn">购买内容</a>
    </div>
    <van-popup
      v-model="qrcodeShow"
      round
      close-icon-position="top-right"
      closeable
      class="qrCodeBox"
      :z-index="2201"
      get-container="body"
    >
      <span class="popupTit">立即支付</span>
      <div class="payNum">
        ￥
        <span>{{ themeCon._data.price }}</span>
      </div>
      <div class="payType">
        <span class="typeLeft">支付方式</span>
        <span class="typeRight">
          <i class="icon iconfont icon-wepay"></i>微信支付
        </span>
      </div>
      <img :src="codeUrl" alt="微信支付二维码" class="qrCode" />
      <p class="payTip">微信识别二维码支付</p>
    </van-popup>
    <div class="loadFix" v-if="payLoading">
      <div class="loadMask"></div>
      <van-loading color="#f7f7f7" class="loadIcon" type="spinner" />
    </div>
    <PayMethod
      v-if="userDet"
      :data="payList"
      v-model="show"
      :pwd-value="value"
      :money="themeCon._data.price"
      :balance="walletBalance"
      :walletStatus="userDet._data.canWalletPay"
      payUrl="setup-pay-pwd"
      @oninput="onInput"
      @delete="onDelete"
      @close="onClose"
      :error="errorInfo"
      @payImmediatelyClick="payImmediatelyClick"
    ></PayMethod>
  </div>
</template>

<script>
// var player = TCPlayer("player-container-id", { // player-container-id 为播放器容器ID，必须与html中一致
//     fileID: "5285890799770145722", // 请传入需要播放的视频fileID 必须
//     appID: "1251099537", // 请传入点播帐号的appID 必须
//     autoplay: false //是否自动播放
//     //其他参数请在开发文档中查看
// });
// var player = TCPlayer("player-container-id", { // player-container-id 为播放器容器ID，必须与html中一致
//     fileID: "5285890799770145722", // 请传入需要播放的视频fileID 必须
//     appID: "1251099537", // 请传入点播帐号的appID 必须
//     autoplay: false //是否自动播放
//     //其他参数请在开发文档中查看
// });
</script>

<script>
import mSiteVideoDetailsCon from "../../../../controllers/m_site/circle/details/videoDetailsCon";
import PayMethod from "../../../../view/m_site/common/pay/paymentMethodView";
import "../../../../defaultLess/m_site/common/common.less";
import "../../../../defaultLess/m_site/modules/circle.less";
export default {
  name: "videoDetailsView",
  components: {
    PayMethod
  },
  ...mSiteVideoDetailsCon
};
</script>
