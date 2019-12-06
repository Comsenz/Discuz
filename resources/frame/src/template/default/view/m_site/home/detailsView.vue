<!--移动端首页模板-->

<template>
    <div class="circleCon">
    	<comHeader title="详情" :menuIconShow="true"></comHeader>
    	<div class="content marBfixed" v-if="themeShow">
		    <div class="cirPostCon">
		    	<div class="postTop">
		    		<div class="postPer">
              <img :src="themeCon.user().avatarUrl()" alt="" class="postHead" v-if="themeCon.user().avatarUrl() == '' && themeCon.user().avatarUrl() == null">
              <img src="../../../../../../static/images/noavatar.gif" class="postHead" v-else="">
		    			<div class="perDet">
		    				<div class="perName">{{themeCon.user().username()}}</div>
		    				<div class="postTime">{{themeCon.user().createdAt()}}</div>
		    			</div>
		    		</div>
		    		<div class="postOpera">
		    			<span class="icon iconfont icon-top"></span>
		    		</div>
		    	</div>
		    	<div class="postContent">
		    		<a href="javascript:;">{{themeCon.firstPost().content()}}</a>
		    	</div>
		    	<div class="postImgBox">
		    		<div class="postImgList">
		    			<img src="../../../../../../static/images/noavatar.gif" class="postPictures">
		    			<img src="../../../../../../static/images/noavatar.gif" class="postPictures">
		    		</div>
		    	</div>
		    	<div class="uploadFileList">
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-pdf"></span>
		    			<span class="fileName">文档名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-rar"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-word"></span>
		    			<span class="fileName">文档名称名称名称名称名称名称.doc</span>
		    		</a>
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-pdf"></span>
		    			<span class="fileName">文档名称称名称名称.doc</span>
		    		</a>
		    	</div>
		    	<div class="postDetBot">
		    		<span class="readNum">{{themeCon.viewCount()}}&nbsp;阅读</span>
		    		<!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
            <div class="screen" @click="bindScreen">
            	<span>管理</span>
            	<span class="icon iconfont icon-down-menu jtGrayB"></span>
            	<div class="themeList" v-if="showScreen">
            		<a href="javascript:;"  @click="themeOpera(themeCon.firstPost().id(),cho.type,themeCon.category().id(),themeCon.firstPost().content())" v-for="(cho,index) in themeChoList" :key="index">{{cho.typeWo}}</a>
            	</div>
            </div>
		    		<a href="javascript:;" class="postDetR" @click="themeOpera(themeCon.firstPost().id(),1,themeCon.category().id(),themeCon.firstPost().content())">收藏</a>
		    		<a href="javascript:;" class="postDetR">分享</a>
		    	</div>
		    </div>

        <div class="gap"></div>
        <div class="commentBox">
          <div class="likeBox" v-if="themeCon.firstPost().likedUsers().length>0">
            <span class="icon iconfont icon-praise-after"></span>
            <a href="javascript:;" v-for="like in themeCon.firstPost().likedUsers()" @click="jumpPerDet(like.id())">{{like.username() + ','}}</a>&nbsp;等<span>{{themeCon.firstPost().likeCount()}}</span>个人觉得很赞
          </div>
          <div class="payPer" v-if="themeCon.rewardedUsers().length>0">
            <span class="icon iconfont icon-money"></span>
            <img v-for="reward in themeCon.rewardedUsers()" v-if="reward.avatarUrl()" :src="reward.avatarUrl()" class="payPerHead">
            <img v-else="" src="../../../../../../static/images/noavatar.gif" class="payPerHead">
          </div>
          <div v-for="item in themeCon.posts()">
            <div class="commentPostDet">
              <div class="postTop">
                <div class="postPer">
                  <img v-if="item.user().avatarUrl()" :src="item.user().avatarUrl()" class="postHead">
                  <img v-else="" src="../../../../../../static/images/noavatar.gif" class="postHead">
                  <div class="perDet">
                    <div class="perName">{{item.user().username()}}</div>
                    <div class="postTime">{{item.updatedAt()}}</div>
                  </div>
                </div>
              </div>
              <div class="postContent">
                <!-- <a href="javascript:;"><blockquote class="quoteCon">dsfhjkdshfkjdhfkjdhk</blockquote>{{item.content()}}</a> -->
                <a href="javascript:;">{{item.content()}}</a>
              </div>
            </div>
            <div class="commentOpera padT22">
              <a @click="replyOpera(item.id(),'1')">删除</a>
              <a v-if="item.isLiked()" @click="replyOpera(item.id(),'2')"><span class="icon iconfont icon-praise-after"></span>{{item.likeCount()}}</a>
              <a v-else="" @click="replyOpera(item.id(),'2')"><span class="icon iconfont icon-like"></span>{{item.likeCount()}}</a>
              <a class="icon iconfont icon-review" @click="replyToJump(themeCon.id(),item.id(),item.content())"></a>
            </div>
          </div>
        </div>
        <div class="detailsFooter">
          <div class="footChi" @click="replyToJump(themeCon.id(),false,false)">
            <span class="icon iconfont icon-review"></span>
            回复
          </div>
          <div class="footChi" @click="replyOpera(themeCon.firstPost().id(),'2')">
            <span class="icon iconfont icon-like"></span>
            赞
          </div>
          <div class="footChi" @click="showRewardPopup">
            <span class="icon iconfont icon-reward"></span>
            打赏
          </div>
        </div>
        <van-popup
          class="rewardPopup"
          v-model="rewardShow"
          closeable
          close-icon-position="top-right"
          position="bottom"
          :style="{ width: '100%' }"
        >
          <span class="support">支持作者继续创作</span>
          <div class="rewardMonBox">
            <div class="moneyChi" v-for="(rewardChi,i) in rewardNumList" :key="i"  @click="rewardPay(rewardChi.rewardNum)">
              <span>{{rewardChi.rewardNum}}</span>元
            </div>
          </div>
       </van-popup>
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
        <img :src="codeUrl" alt="" class="qrCode">
        <p class="payTip">微信识别二维码支付</p>
       </van-popup>
     </div>
    </div>
</template>

<script>
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import mSiteDetailsCon from '../../../controllers/m_site/circle/detailsCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "detailsView",
    components:{
    	comHeader
    },
    ...mSiteDetailsCon
}



</script>
