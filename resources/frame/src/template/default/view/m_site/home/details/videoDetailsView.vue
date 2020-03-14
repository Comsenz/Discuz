<template>
  <div v-model="themeCon">
    <div class="postTop">
      <div class="postPer">
        <img
          v-if="themeCon.user && themeCon.user._data.avatarUrl"
          :src="themeCon.user._data.avatarUrl"
          alt=""
          @click="jumpPerDet(themeCon.user._data.id)"
          class="postHead"
        />
        <img
          :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
          class="postHead"
          v-else
          @click="jumpPerDet(themeCon.user._data.id)"
        />
        <div class="perDet">
          <div
            class="perName"
            v-if="themeCon.user"
            @click="jumpPerDet(themeCon.user._data.id)"
          >{{themeCon.user._data.username}}</div>
          <div class="perName" v-else>该用户已被删除</div>
          <div class="postTime">{{$moment(themeCon._data.createdAt).format('YYYY-MM-DD HH:mm')}}</div>
        </div>
      </div>
      <div class="postOpera">
        <span class="icon iconfont icon-top" v-if="themeCon._data.isSticky"></span>
      </div>
    </div>
    <div class="longTextContent" v-html="themeCon.firstPost._data.contentHtml"></div>
    <div class="videoBox" v-if="themeCon._data.price > 0 && !themeCon._data.paid">
      <img class="videoCover" v-if="themeCon.threadVideo._data.file_id == '' || themeCon.threadVideo._data.file_id == null" :src="themeCon.threadVideo._data.cover_url" alt="">
    </div>
    <div class="videoBox" v-else>
      <img v-if="themeCon.threadVideo._data.status == 0" :src="appConfig.staticBaseUrl+'/images/transcoding.png'" alt="" class="transcodingCover">
      <div class="videoContent" v-if="themeCon.threadVideo._data.file_id != '' && themeCon.threadVideo._data.file_id != null && themeCon.threadVideo._data.status == 1">
        <!-- <img :src="coverUrl" alt="" :style="{'display': loadCover?'block':'none'}" ref="coverShow">
        <video :style="{'display': loadVideo?'block':'none'}" ref="videoShow" :id="tcPlayerId" preload="auto" width="100%" playsinline webkit-playsinline x5-playsinline></video> -->
        <img :src="coverUrl" v-show="loadCover" alt="" ref="coverShow">
        <video v-show="loadVideo" :id="tcPlayerId" preload="auto" width="100%" playsinline webkit-playsinline x5-playsinline></video>
      </div>
      
    </div>
    <div class="payTipBox" v-if="themeCon._data.price > 0 && !themeCon._data.paid">
      <p class="tipPrice">
        本内容需向作者支付&nbsp;
        <span>{{themeCon._data.price}}</span>&nbsp;元&nbsp;才能浏览
      </p>
      <a href="javascript:;" @click="buyTheme" class="buyBtn">购买内容</a>
    </div>
    <van-popup
      v-model="qrcodeShow"
      round
      close-icon-position="top-right"
      closeable
      class="qrCodeBox"
      get-container="body"
    >
      <span class="popupTit">立即支付</span>
      <div class="payNum">
        ￥
        <span>{{themeCon._data.price}}</span>
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
    //     appID: "1251099537", // 请传入点播账号的appID 必须
    //     autoplay: false //是否自动播放
    //     //其他参数请在开发文档中查看
    // });
    // var player = TCPlayer("player-container-id", { // player-container-id 为播放器容器ID，必须与html中一致
    //     fileID: "5285890799770145722", // 请传入需要播放的视频fileID 必须
    //     appID: "1251099537", // 请传入点播账号的appID 必须
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
