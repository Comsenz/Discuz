<!--移动端详情页模板-->

<template>
  <!-- 付费站点 已登录且当前用户已付费 -->
  <div class="circleCon">
    <comHeader title="详情" :menuIconShow="menuStatus"></comHeader>
    <van-pull-refresh v-model="isLoading" :head-height="0" @refresh="onRefresh">
      <div class="content marBfixed" v-if="themeShow">
        <div class="contentExamine" v-show="contentExamine">{{examineWord}}</div>
        <div class="cirPostCon">
          <!-- 普通主题内容组件 -->
          <normalDetail
            v-if="themeCon._data.type == 0"
            :themeCon="themeCon"
            :firstpostImageListProp="firstpostImageList"
          ></normalDetail>
          <!-- 付费长文内容组件 -->
          <longTextDetail
            v-if="themeCon._data.type == 1"
            :themeCon="themeCon"
            :userDet="userDet"
            :firstpostImageListProp="firstpostImageList"
            v-on:listenToChildEvent="detailsLoad"
          ></longTextDetail>

          <!-- 视屏内容组件 -->
          <videoDetail
            v-if="themeCon._data.type == 2"
            :themeCon="themeCon"
            :userDet="userDet"
            :firstpostImageListProp="firstpostImageList"
            v-on:listenToChildEvent="detailsLoad"
          ></videoDetail>

          <div class="postDetBot">
            <span class="readNum">{{themeCon._data.postCount-1}}&nbsp;回复</span>
            <!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
            <div
              class="screen"
              ref="screenBox"
              @click="bindScreen"
              v-if="themeCon._data.canEssence || themeCon._data.canSticky || themeCon._data.canHide || themeCon._data.canEdit"
            >
              <span>管理</span>
              <span class="icon iconfont icon-down-menu jtGrayB"></span>
              <div class="themeList" v-if="showScreen">
                <a
                  href="javascript:;"
                  @click="themeOpera(themeCon.firstPost._data.id,2,themeCon.category._data.id,themeCon.firstPost._data.content)"
                  v-if="themeCon._data.canEssence"
                >{{essenceFlag}}</a>
                <a
                  href="javascript:;"
                  @click="themeOpera(themeCon.firstPost._data.id,3,themeCon.category._data.id,themeCon.firstPost._data.content)"
                  v-if="themeCon._data.canSticky"
                >{{stickyFlag}}</a>
                <a
                  href="javascript:;"
                  @click="themeOpera(themeCon.firstPost._data.id,4,themeCon.category._data.id,themeCon.firstPost._data.content)"
                  v-if="themeCon._data.canHide"
                >删除</a>
                <a
                  href="javascript:;"
                  @click="themeOpera(themeCon.firstPost._data.id,5,themeCon.category._data.id,themeCon.firstPost._data.content)"
                  v-if="themeCon.firstPost._data.canEdit"
                >编辑</a>
              </div>
            </div>
            <a
              href="javascript:;"
              class="postDetR"
              @click="themeOpera(themeCon.firstPost._data.id,1,themeCon.category._data.id,themeCon.firstPost._data.content)"
            >{{collectFlag}}</a>
            <a href="javascript:;" class="postDetR" @click="shareTheme">分享</a>
            <input type="button" hidden value="点击复制代码" />
          </div>
        </div>

        <div class="gap"></div>
        <div class="commentBox">
          <div
            class="likeBox"
            v-if="themeCon.firstPost.likedUsers.length>0 && likeLen"
            v-model="userArrStatus"
          >
            <span class="icon iconfont icon-praise-after"></span>
            <!-- <span id="likedUserList" class="likedUserList"></span> -->
            <span
              id="likedUserList"
              class="likedUserList"
              v-html="userArr(themeCon.firstPost.likedUsers,false)"
            ></span>
            <!-- <a  @click="jumpPerDet(like._data.id)">{{userArr(themeCon.firstPost.likedUsers)}}</a> -->
            <!-- <a href="javascript:;" v-for="like in themeCon.firstPost.likedUsers" @click="jumpPerDet(like.id)">{{like._data.username + ','}}</a><i v-if="themeCon.firstPost._data.likeCount>10">&nbsp;等<span>{{themeCon.firstPost._data.likeCount}}</span>个人觉得很赞</i> -->
          </div>
          <div
            class="payPer"
            v-if="themeCon.rewardedUsers.length>0 && themeCon.rewardedUsers.length<=11"
          >
            <span class="icon iconfont icon-money"></span>
            <div
              class="payPerHeaChi"
              v-for="(reward,rewardInd) in themeCon.rewardedUsers"
              :key="rewardInd"
            >
              <img
                v-if="reward._data.avatarUrl"
                :src="reward._data.avatarUrl"
                @click="jumpPerDet(reward._data.id)"
                class="payPerHead"
              />
              <img
                v-else
                :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
                @click="jumpPerDet(reward._data.id)"
                class="payPerHead"
              />
            </div>
            <!-- <span class="foldTip" v-if="themeCon.rewardedUsers.length>5 && rewardTipShow">等{{themeCon.rewardedUsers.length}}人进行了打赏</span>
            <i @click="rewardIsFold(themeCon.rewardedUsers.length)" class="foldTag">{{rewardTipFlag}}<span class="icon iconfont icon-down-menu" :class="{'rotate180':rewardTipShow}"></span></i>-->
          </div>
          <div class="payPer" v-if="themeCon.rewardedUsers.length>11">
            <span class="icon iconfont icon-money"></span>
            <div
              class="payPerHeaChi"
              v-for="(reward,rewardInd) in themeCon.rewardedUsers"
              :key="rewardInd"
              v-if="rewardInd<limitLen"
            >
              <img
                v-if="reward._data.avatarUrl"
                :src="reward._data.avatarUrl"
                @click="jumpPerDet(reward._data.id)"
                class="payPerHead"
              />
              <img
                v-else
                :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
                @click="jumpPerDet(reward._data.id)"
                class="payPerHead"
              />
            </div>
            <span
              class="foldTip"
              v-if="themeCon.rewardedUsers.length>5 && rewardTipShow"
            >等{{themeCon.rewardedUsers.length}}人进行了打赏</span>
            <!-- <span class="foldTip" v-if="themeCon.rewardedUsers.length>5 && rewardTipShow">等{{themeCon.rewardedUsers.length}}人进行了打赏</span>
            <i @click="rewardIsFold(themeCon.rewardedUsers.length)" class="foldTag">{{rewardTipFlag}}<span class="icon iconfont icon-down-menu" :class="{'rotate180':rewardTipShow}"></span></i>-->
          </div>
          <van-list
            v-model="loading"
            :finished="finished"
            :offset="offset"
            finished-text="没有更多了"
            @load="onLoad"
            :immediate-check="false"
          >
            <div v-for="(item,postIndex) in themeCon.posts" :key="postIndex">
              <div class="commentPostDet">
                <div class="postTop">
                  <div class="postPer">
                    <img
                      v-if="item.user && item.user._data.avatarUrl"
                      :src="item.user._data.avatarUrl"
                      class="postHead"
                      @click="jumpPerDet(item.user._data.id)"
                    />
                    <img
                      v-else
                      :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
                      class="postHead"
                      @click="jumpPerDet(item.user._data.id)"
                    />
                    <div class="perDet">
                      <div
                        class="perName"
                        v-if="item.user && item.user._data.username"
                        @click="jumpPerDet(item.user._data.id)"
                      >
                        <a
                          href="javascript:;"
                          v-if="item.user"
                          @click="jumpPerDet(item.user._data.id)"
                        >{{item.user._data.username}}</a>
                        <a href="javascript:;" v-else>该用户已被删除</a>
                        <span class="font9" v-if="item._data.replyUserId">回复</span>
                        <a
                          href="javascript:;"
                          v-if="item._data.replyUserId && item.replyUser"
                          @click="jumpPerDet(item.user._data.id)"
                        >{{item.replyUser._data.username}}</a>
                        <a
                          href="javascript:;"
                          v-else-if="item._data.replyUserId && !item.replyUser"
                        >该用户已被删除</a>
                      </div>
                      <div class="perName" v-else>该用户已被删除</div>

                      <div
                        class="postTime"
                      >{{$moment(item._data.createdAt).format('YYYY-MM-DD HH:mm')}}</div>
                    </div>
                  </div>
                </div>
                <div class="postContent" v-html="item._data.contentHtml">
                  <!-- <a href="javascript:;"><blockquote class="quoteCon">dsfhjkdshfkjdhfkjdhk</blockquote>{{item.content()}}</a> -->
                </div>
                <div class="postImgBox" v-if="item.images.length>0">
                  <div class="themeImgList moreImg">
                    <div v-if="isWeixin || isPhone">
                      <van-image
                        lazy-load
                        v-for="(image,index)  in item.images"
                        :src="image._data.thumbUrl"
                        :key="index"
                        @click="imageSwiper(index, 'replyImg', postIndex)"
                      />
                    </div>
                    <div v-else>
                      <van-image
                        lazy-load
                        v-for="(image,index)  in item.images"
                        :src="image._data.thumbUrl"
                        :key="index"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="commentOpera padT22">
                <a v-if="item._data.canHide" @click="deleteOpear(item._data.id,postIndex)">删除</a>
                <a
                  v-if="item._data.isLiked"
                  @click="replyOpera(item._data.id,'2',item._data.isLiked,item._data.canLike,postIndex)"
                >
                  <span class="icon iconfont icon-praise-after" :class="{'icon-like': likedClass}"></span>
                  {{item._data.likeCount}}
                </a>
                <a
                  v-else
                  @click="replyOpera(item._data.id,'2',item._data.isLiked,item._data.canLike,postIndex)"
                >
                  <span class="icon iconfont icon-like" :class="{'icon-praise-after': likedClass}"></span>
                  {{item._data.likeCount}}
                </a>
                <a
                  class="icon iconfont icon-review"
                  @click="replyToJump(themeCon._data.id,item._data.id,item._data.content)"
                ></a>
              </div>
            </div>
          </van-list>
        </div>
      </div>
    </van-pull-refresh>
    <div class="detailsFooter" id="detailsFooter" :class="{'twoChi':twoChi}" v-if="themeCon">
      <div class="footChi" @click="replyToJump(themeCon._data.id,0,false)">
        <span class="icon iconfont icon-review"></span>
        回复
      </div>
      <div class="footChi" @click="footReplyOpera(themeCon.firstPost._data.id,'3',themeIsLiked)">
        <span v-if="!(themeIsLiked)" class="icon iconfont icon-like"></span>
        <span v-else class="icon iconfont icon-praise-after"></span>
        赞
      </div>
      <div class="footChi" @click="showRewardPopup" v-if="wxpay=='1' || wxpay == true">
        <span class="icon iconfont icon-reward"></span>
        打赏
      </div>
    </div>
    <van-popup
      class="rewardPopup"
      id="rewardPopup"
      v-model="rewardShow"
      closeable
      close-icon-position="top-right"
      position="bottom"
      :style="{'overflow': 'hidden','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0','width': (!isPhone && !isWeixin) ? '640px' : '100%'}"
    >
      <span class="support">支持作者继续创作</span>
      <div class="rewardMonBox">
        <a
          class="moneyChi"
          v-for="(rewardChi,i) in rewardNumList"
          :key="i"
          @click="payClick(rewardChi.rewardNum)"
        >
          <span>{{rewardChi.rewardNum}}</span>元
        </a>
      </div>
    </van-popup>
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
        <span>{{amountNum}}</span>
      </div>
      <div class="payType">
        <span class="typeLeft">支付方式</span>
        <span class="typeRight">
          <i class="icon iconfont icon-wepay"></i>微信支付
        </span>
      </div>
      <img :src="codeUrl" alt class="qrCode" />
      <p class="payTip">微信识别二维码支付</p>
    </van-popup>
    <PayMethod
      v-if="userDet"
      :data="payList"
      v-model="show"
      :money="amountNum"
      :balance="walletBalance"
      :walletStatus="userDet._data.canWalletPay"
      :pwd-value="value"
      payUrl="setup-pay-pwd"
      @oninput="onInput"
      @delete="onDelete"
      @close="onClose"
      :error="errorInfo"
      @payImmediatelyClick="payImmediatelyClick"
    ></PayMethod>

    <div class="loadFix" v-if="payLoading">
      <div class="loadMask"></div>
      <van-loading color="#333333" class="loadIcon" type="spinner" />
    </div>

    <van-button
      type="primary"
      v-if="loginBtnFix"
      class="loginBtnFix"
      :style="{'overflow': 'hidden','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2 + 192+'px' : '30%','width': (!isPhone && !isWeixin) ? '256px' : '40%'}"
      @click="loginJump(1)"
      :class="{'hide':loginHide}"
    >{{loginWord}}</van-button>
  </div>
</template>

<script>
import comHeader from "../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader";
import PayMethod from "../../../view/m_site/common/pay/paymentMethodView";
import longTextDetail from "../home/details/longTextDetailsView";
import normalDetail from "../home/details/normalDetailsView";
import videoDetail from "../home/details/videoDetailsView";
import mSiteDetailsCon from "../../../controllers/m_site/circle/detailsCon";
import "../../../defaultLess/m_site/common/common.less";
import "../../../defaultLess/m_site/modules/circle.less";
export default {
  name: "detailsView",
  components: {
    comHeader,
    normalDetail,
    longTextDetail,
    videoDetail,
    PayMethod
  },
  ...mSiteDetailsCon
};
</script>
