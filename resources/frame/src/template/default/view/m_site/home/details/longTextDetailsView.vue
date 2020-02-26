<template>
    <div v-model="themeCon">
        <div class="postTop">
		    <div class="postPer">
                <img v-if="themeCon.user && themeCon.user._data.avatarUrl" :src="themeCon.user._data.avatarUrl" alt="" @click="jumpPerDet(themeCon.user._data.id)" class="postHead">
                <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="" @click="jumpPerDet(themeCon.user._data.id)">
                <div class="perDet">
                    <div class="perName" v-if="themeCon.user" @click="jumpPerDet(themeCon.user._data.id)">{{themeCon.user._data.username}}</div>
                    <div class="perName" v-else="">该用户已被删除</div>
                    <div class="postTime">{{$moment(themeCon._data.createdAt).format('YYYY-MM-DD HH:mm')}}</div>
                </div>
            </div>
            <div class="postOpera">
                <span class="icon iconfont icon-top" v-if="themeCon._data.isSticky"></span>
            </div>
        </div>
        <div class="postTitle">{{themeCon._data.title}}</div>
        <div class="longTextContent" v-html="themeCon.firstPost._data.contentHtml"></div>
        <!-- <div class="payTipBox" v-if="themeCon._data.price>0"> -->
        <div class="payTipBox" v-if="!themeCon._data.paid && themeCon._data.price>0">
            <p class="tipPrice">本内容需向作者支付&nbsp;<span>{{themeCon._data.price}}</span>&nbsp;元&nbsp;才能浏览</p>
            <a href="javascript:;" @click="buyTheme" class="buyBtn">购买内容</a>
        </div>
        <div class="postImgBox" v-if="firstpostImageListProp.length>0">
            <div class="postImgList">
              <div v-if="isWeixin || isPhone">
                <van-image
                    lazy-load
                    v-for="(image,index)  in firstpostImageListProp"
                    key = index
                    :src="image"
                    @click="imageSwiper(index,'detailImg')"
                    :key="index"
                />
              </div>
              <div v-else="">
                <van-image
                    lazy-load
                    v-for="(image,index)  in firstpostImageListProp"
                    key = index
                    :src="image"
                    :key="index"
                />
              </div>
            </div>
		</div>
        <div class="uploadFileList" v-if="isiOS && themeCon.firstPost.attachments.length>0">
		    <a @click="downAttachment(attachment._data.url)" class="fileChi" v-for="(attachment,attaindex)  in themeCon.firstPost.attachments" :key="attaindex">
		    	<span v-if="attachment._data.extension === 'rar'" class="icon iconfont icon-rar"></span>
                <span v-if="attachment._data.extension === 'zip'" class="icon iconfont icon-rar"></span>
		    	<span v-else-if="attachment._data.extension === 'doc'" class="icon iconfont icon-word"></span>
                <span v-else-if="attachment._data.extension === 'docx'" class="icon iconfont icon-word"></span>
                <span v-else-if="attachment._data.extension === 'pdf'" class="icon iconfont icon-pdf"></span>
                <span v-else-if="attachment._data.extension === 'jpg'" class="icon iconfont icon-jpg"></span>
                <span v-else-if="attachment._data.extension === 'mp'" class="icon iconfont icon-mp3"></span>
                <span v-else-if="attachment._data.extension === 'mp1'" class="icon iconfont icon-mp4"></span>
                <span v-else-if="attachment._data.extension === 'png'" class="icon iconfont icon-PNG"></span>
                <span v-else-if="attachment._data.extension === 'ppt'" class="icon iconfont icon-ppt"></span>
                <span v-else-if="attachment._data.extension === 'swf'" class="icon iconfont icon-swf"></span>
                <span v-else-if="attachment._data.extension === 'TIFF'" class="icon iconfont icon-TIFF"></span>
                <span v-else-if="attachment._data.extension === 'txt'" class="icon iconfont icon-txt"></span>
                <span v-else-if="attachment._data.extension === 'xls'" class="icon iconfont icon-xls"></span>
                <span v-else="" class="icon iconfont icon-doubt"></span>
		    	<span class="fileName">{{attachment._data.fileName}}</span>
		    </a>
        </div>
        <div class="uploadFileList" v-if="themeCon.firstPost.attachments.length>0">
            <a :href="attachment._data.url" class="fileChi" v-for="(attachment,attaindex)  in themeCon.firstPost.attachments" :key="attaindex" download>
                <span v-if="attachment._data.extension === 'rar'" class="icon iconfont icon-rar"></span>
                <span v-if="attachment._data.extension === 'zip'" class="icon iconfont icon-rar"></span>
                <span v-else-if="attachment._data.extension === 'doc'" class="icon iconfont icon-word"></span>
                <span v-else-if="attachment._data.extension === 'docx'" class="icon iconfont icon-word"></span>
                <span v-else-if="attachment._data.extension === 'pdf'" class="icon iconfont icon-pdf"></span>
                <span v-else-if="attachment._data.extension === 'jpg'" class="icon iconfont icon-jpg"></span>
                <span v-else-if="attachment._data.extension === 'mp'" class="icon iconfont icon-mp3"></span>
                <span v-else-if="attachment._data.extension === 'mp1'" class="icon iconfont icon-mp4"></span>
                <span v-else-if="attachment._data.extension === 'png'" class="icon iconfont icon-PNG"></span>
                <span v-else-if="attachment._data.extension === 'ppt'" class="icon iconfont icon-ppt"></span>
                <span v-else-if="attachment._data.extension === 'swf'" class="icon iconfont icon-swf"></span>
                <span v-else-if="attachment._data.extension === 'TIFF'" class="icon iconfont icon-TIFF"></span>
                <span v-else-if="attachment._data.extension === 'txt'" class="icon iconfont icon-txt"></span>
                <span v-else-if="attachment._data.extension === 'xls'" class="icon iconfont icon-xls"></span>
                <span v-else="" class="icon iconfont icon-doubt"></span>
            	<span class="fileName">{{attachment._data.fileName}}</span>
            </a>
		</div>
        <van-popup
            v-model="qrcodeShow"
            round
            close-icon-position="top-right"
            closeable
            class="qrCodeBox"
            get-container="body">
            <span class="popupTit">立即支付</span>
            <div class="payNum">￥<span>{{themeCon._data.price}}</span></div>
            <div class="payType">
            <span class="typeLeft">支付方式</span>
            <span class="typeRight"><i class="icon iconfont icon-wepay"></i>微信支付</span>
            </div>
            <img :src="codeUrl" alt="微信支付二维码" class="qrCode">
            <p class="payTip">微信识别二维码支付</p>
        </van-popup>

      <PayMethod
        v-if="userDet"
        :data="payList"
        v-model="show"
        :money="themeCon._data.price"
        :balance="walletBalance"
        :walletStatus="userDet._data.canWalletPay"
        payUrl="setup-pay-pwd"
        @oninput="onInput"
        @delete="onDelete"
        @close="onClose"
        :error="errorInfo"
        @payImmediatelyClick="payImmediatelyClick">
      </PayMethod>
    </div>
</template>


<script>
import mSiteLongTextDetailsCon from '../../../../controllers/m_site/circle/details/longTextDetailsCon';
import PayMethod from '../../../../view/m_site/common/pay/paymentMethodView';
import  '../../../../defaultLess/m_site/common/common.less';
import  '../../../../defaultLess/m_site/modules/circle.less';
export default {
    name: "longTextDetailsView",
    components:{
    	PayMethod,
    },
    ...mSiteLongTextDetailsCon
}
</script>