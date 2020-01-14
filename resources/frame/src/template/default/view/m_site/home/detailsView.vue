<!--移动端首页模板-->

<template>
    <!-- 付费站点 已登录且当前用户已付费 -->
    <div class="circleCon">
    	<comHeader title="详情" :menuIconShow="menuStatus"></comHeader>
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    	<div class="content marBfixed" v-if="themeShow">
		    <div class="cirPostCon">
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
		    	<div class="postContent">
		    		<a v-html="themeCon.firstPost._data.contentHtml"></a>
		    	</div>
		    	<div class="postImgBox">
            <div class="postImgList">
              <van-image
                  lazy-load
                  v-for="(image,index)  in firstpostImageList"
                  key = index
                  :src="image"
                  @click="imageSwiper(index,'detailImg')"
                  :key="index"
              />
            </div>
		    	</div>
		    	<div class="uploadFileList" v-if="isiOS">
		    		<a @click="downAttachment(attachment._data.url)" class="fileChi" v-for="(attachment,attaindex)  in themeCon.firstPost.attachments" :key="attaindex">
              <!-- <a :href="attachment._data.url" class="fileChi"  v-for="(attachment,attaindex)  in themeCon.firstPost.attachments" :key="attaindex" download> -->
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
            <div class="uploadFileList" v-else="">
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
		    	<div class="postDetBot">
		    		<span class="readNum">{{themeCon._data.viewCount}}&nbsp;阅读</span>
		    		<!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
            <div class="screen" ref="screenBox" @click="bindScreen" v-if="themeCon._data.canEssence || themeCon._data.canSticky || themeCon._data.canHide || themeCon._data.canEdit">
            	<span>管理</span>
            	<span class="icon iconfont icon-down-menu jtGrayB"></span>
            	<div class="themeList" v-if="showScreen">
            		<a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,2,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canEssence">{{essenceFlag}}</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,3,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canSticky">{{stickyFlag}}</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,4,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canHide">删除</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,5,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon.firstPost._data.canEdit">编辑</a>
            	</div>
            </div>
		    		<a href="javascript:;" class="postDetR" @click="themeOpera(themeCon.firstPost._data.id,1,themeCon.category._data.id,themeCon.firstPost._data.content)">{{collectFlag}}</a>
		    		<a href="javascript:;" class="postDetR" @click="shareTheme">分享</a>
            <input type="button" hidden value="点击复制代码" />
		    	</div>
		    </div>

        <div class="gap"></div>
        <div class="commentBox">
          <div class="likeBox" v-if="themeCon.firstPost.likedUsers.length>0">
            <span class="icon iconfont icon-praise-after"></span>
             <span id="likedUserList" v-html="userArr(themeCon.firstPost.likedUsers)"></span>
            <!-- <a  @click="jumpPerDet(like._data.id)">{{userArr(themeCon.firstPost.likedUsers)}}</a> -->
            <!-- <a href="javascript:;" v-for="like in themeCon.firstPost.likedUsers" @click="jumpPerDet(like.id)">{{like._data.username + ','}}</a><i v-if="themeCon.firstPost._data.likeCount>10">&nbsp;等<span>{{themeCon.firstPost._data.likeCount}}</span>个人觉得很赞</i> -->
          </div>
          <div class="payPer" v-if="themeCon.rewardedUsers.length>0">
            <span class="icon iconfont icon-money"></span>
            <img v-for="reward in themeCon.rewardedUsers" v-if="reward._data.avatarUrl" :src="reward._data.avatarUrl" @click="jumpPerDet(reward._data.id)" class="payPerHead">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" @click="jumpPerDet(reward._data.id)" class="payPerHead">
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
                  <img v-if="item.user && item.user._data.avatarUrl" :src="item.user._data.avatarUrl" class="postHead" @click="jumpPerDet(item.user._data.id)" >
                  <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" @click="jumpPerDet(item.user._data.id)">
                  <div class="perDet">
                    <div class="perName" v-if="item.user && item.user._data.username" @click="jumpPerDet(item.user._data.id)">{{item.user._data.username}}</div>
                    <div class="perName" v-else="">该用户已被删除</div>
                    <div class="postTime">{{$moment(item._data.updatedAt).format('YYYY-MM-DD HH:mm')}}</div>
                  </div>
                </div>
              </div>
              <div class="postContent">
                <!-- <a href="javascript:;"><blockquote class="quoteCon">dsfhjkdshfkjdhfkjdhk</blockquote>{{item.content()}}</a> -->
                <a href="javascript:;" v-html="item._data.contentHtml"></a>
              </div>
              <div class="postImgBox">
                <div class="themeImgList moreImg">
                  <van-image
                      lazy-load
                      v-for="(image,index)  in item.images"
                      :src="image._data.thumbUrl"
                      :key="index"
                      @click="imageSwiper(index, 'replyImg', postIndex)"
                    />
                </div>
              </div>
            </div>
            <div class="commentOpera padT22">
              <a v-if="item._data.canHide" @click="deleteOpear(item._data.id,postIndex)">删除</a>
              <a v-if="item._data.isLiked" @click="replyOpera(item._data.id,'2',item._data.isLiked,item._data.canLike,postIndex)"><span class="icon iconfont icon-praise-after" :class="{'icon-like': likedClass}"></span>{{item._data.likeCount}}</a>
              <a v-else="" @click="replyOpera(item._data.id,'2',item._data.isLiked,item._data.canLike,postIndex)"><span class="icon iconfont icon-like":class="{'icon-praise-after': likedClass}"></span>{{item._data.likeCount}}</a>
              <a class="icon iconfont icon-review" @click="replyToJump(themeCon._data.id,item._data.id,item._data.content)"></a>
            </div>

          </div>
          </van-list>
        </div>

     </div>
     </van-pull-refresh>
     <div class="detailsFooter" id="detailsFooter">
          <div class="footChi" @click="replyToJump(themeCon._data.id,0,false)">
            <span class="icon iconfont icon-review"></span>
            回复
          </div>
          <div class="footChi" @click="footReplyOpera(themeCon.firstPost._data.id,'3',themeCon.firstPost._data.isLiked)">
            <span v-if="!(themeCon.firstPost._data.isLiked)" class="icon iconfont icon-like"></span>
            <span v-else="" class="icon iconfont icon-praise-after"></span>
            赞
          </div>
          <div class="footChi" @click="showRewardPopup">
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
          :style="{ width: '100%' }"
        >
          <span class="support">支持作者继续创作</span>
          <div class="rewardMonBox">
            <a class="moneyChi" v-for="(rewardChi,i) in rewardNumList" :key="i"  @click="payClick(rewardChi.rewardNum)">
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
       <!-- <van-image-preview
         v-model="imageShow"
         :images="firstpostImageList"
         @change="onChangeImgPreview"
       > -->
       <!-- <template v-slot:index>第{{ index }}页</template> -->
     <!-- </van-image-preview> -->
      <van-button type="primary" v-if="loginBtnFix" class="loginBtnFix" @click="loginJump(1)" :class="{'hide':loginHide}">登录 / 注册</van-button>


    </div>
</template>

<script>
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
// import mSiteHeader from '../../../controllers/m_site/common/headerCon';
// import Header from '../../m_site/common//headerView';
import mSiteDetailsCon from '../../../controllers/m_site/circle/detailsCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "detailsView",
    components:{
    	comHeader,
    },
    ...mSiteDetailsCon
}



</script>
