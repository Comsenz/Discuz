<!--移动端首页模板-->

<template>
    <!-- 付费站点 已登录且当前用户已付费 -->
    <div class="circleCon" v-if="situation1">
    	<comHeader title="详情" :menuIconShow="true"></comHeader>
    	<div class="content marBfixed" v-if="themeShow">
		    <div class="cirPostCon">
		    	<div class="postTop">
		    		<div class="postPer">
              <img :src="themeCon.user._data.avatarUrl" alt="" class="postHead" v-if="themeCon.user._data.avatarUrl == '' && themeCon.user._data.avatarUrl == null">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
		    			<div class="perDet">
		    				<div class="perName">{{themeCon.user._data.username}}</div>
		    				<div class="postTime">{{themeCon.user._data.createdAt}}</div>
		    			</div>
		    		</div>
		    		<div class="postOpera">
		    			<span class="icon iconfont icon-top"></span>
		    		</div>
		    	</div>
		    	<div class="postContent">
		    		<a href="javascript:;" v-if="themeCon.firstPost._data.content" v-html="themeCon.firstPost._data.content">22222</a>
		    	</div>
		    	<div class="postImgBox">
            <div class="postImgList">
              <van-image
                fit="none"
                lazy-load
                v-for="(image,index)  in firstpostImageList"
                :src="image"
                @click="imageSwiper"
              />
            </div>
		    	</div>
		    	<div class="uploadFileList" v-for="(attachment,index)  in themeCon.firstPost.attachments" :key="index">
		    		<a href="javascript:;" class="fileChi">
		    			<span class="icon iconfont icon-pdf"></span>
		    		  <!-- <span v-if="attachment._data.fileType = rar" class="icon iconfont icon-rar"></span>
		    		  <span class="icon iconfont icon-word"></span> -->
		    			<span class="fileName">{{attachment._data.fileName}}</span>
		    		</a>
		    	</div>
		    	<div class="postDetBot">
		    		<span class="readNum">{{themeCon._data.viewCount}}&nbsp;阅读</span>
		    		<!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
            <div class="screen" @click="bindScreen" v-if="">
            	<span>管理</span>
            	<span class="icon iconfont icon-down-menu jtGrayB"></span>
            	<div class="themeList" v-if="showScreen">
            		<a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,2,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canEssence">加精</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,3,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canSticky">置顶</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,4,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canDelete">删除</a>
                <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,5,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="">编辑</a>
            	</div>
            </div>
		    		<a href="javascript:;" class="postDetR" @click="themeOpera(themeCon.firstPost._data.id,1,themeCon.category._data.id,themeCon.firstPost._data.content)">收藏</a>
		    		<a href="javascript:;" class="postDetR" @click="shareTheme">分享</a>
            <input type="button" hidden value="点击复制代码" />
		    	</div>
		    </div>

        <div class="gap"></div>
        <div class="commentBox">
          <div class="likeBox" v-if="themeCon.firstPost.likedUsers">
            <span class="icon iconfont icon-praise-after"></span>
            <a href="javascript:;" v-for="like in themeCon.firstPost.likedUsers" @click="jumpPerDet(like.id)">{{like._data.username + ','}}</a>&nbsp;等<span>{{themeCon.firstPost._data.likeCount}}</span>个人觉得很赞
          </div>
          <div class="payPer" v-if="themeCon.rewardedUsers">
            <span class="icon iconfont icon-money"></span>
            <img v-for="reward in themeCon.rewardedUsers" v-if="reward.avatarUrl" :src="reward._data.avatarUrl" class="payPerHead">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
          </div>
          <div v-for="item in themeCon.posts">
            <div class="commentPostDet">
              <div class="postTop">
                <div class="postPer">
                  <img v-if="item.user._data.avatarUrl" :src="item.user._data.avatarUrl" class="postHead">
                  <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
                  <div class="perDet">
                    <div class="perName">{{item.user._data.username}}</div>
                    <div class="postTime">{{item._data.updatedAt}}</div>
                  </div>
                </div>
              </div>
              <div class="postContent">
                <!-- <a href="javascript:;"><blockquote class="quoteCon">dsfhjkdshfkjdhfkjdhk</blockquote>{{item.content()}}</a> -->
                <a href="javascript:;" v-html="item._data.content"></a>
              </div>
            </div>
            <div class="commentOpera padT22">
              <a @click="replyOpera(item._data.id,'1')">删除</a>
              <a v-if="item._data.isLiked" @click="replyOpera(item._data.id,'2',item._data.isLiked)"><span class="icon iconfont icon-praise-after" :class="{'icon-like': likedClass}"></span>{{item._data.likeCount}}</a>
              <a v-else="" @click="replyOpera(item._data.id,'2',item._data.isLiked)"><span class="icon iconfont icon-like":class="{'icon-praise-after': likedClass}"></span>{{item._data.likeCount}}</a>
              <a class="icon iconfont icon-review" @click="replyToJump(themeCon._data.id,item._data.id,item._data.content)"></a>
            </div>
          </div>
        </div>
        <div class="detailsFooter">
          <div class="footChi" @click="replyToJump(themeCon._data.id,false,false)">
            <span class="icon iconfont icon-review"></span>
            回复
          </div>
          <div class="footChi" @click="replyOpera(themeCon.firstPost._data.id,'2',themeCon.firstPost._data.isLiked)">
            <span v-if="themeCon.firstPost._data.isLiked" class="icon iconfont icon-praise-after"></span>
            <span v-else="" class="icon iconfont icon-like"></span>
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
     <van-image-preview
       v-model="imageShow"
       :images="firstpostImageList"
       @change="onChange"
     >
       <template v-slot:index>第{{ index }}页</template>
     </van-image-preview>
    </div>


    <!-- 付费站点 已登录但未付费 -->
    <div class="circleCon" v-else-if="situation2">
      <div v-if="siteInfo">
        <Header :logoShow="true" :perDetShow="true"></Header>
        <div class="gap"></div>
        <div class="circlePL">
        	<div class="circleLoBox">
          	<span class="circleIcon">站点图标</span>
            <img v-if="siteInfo.logo" :src="siteInfo._data.logo" class="circleLogo">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/logo.png'" class="circleLogo">
          </div>
        </div>
        <div class="circleInfo padB0 lastBorNone">
        	<h1 class="cirInfoTit">站点简介</h1>
        	<p class="cirInfoWord">{{siteInfo._data.siteIntroduction}}{{}}</p>
        	<div class="infoItem">
          	<span class="infoItemLeft">创建时间</span>
          	<span class="infoItemRight">{{siteInfo._data.siteInstall}}</span>
          </div>
          <div class="infoItem">
          	<span class="infoItemLeft">加入方式</span>
          	<span class="infoItemRight">付费{{siteInfo._data.price}}元，有效期自加入起{{siteInfo.day}}天</span>
          </div>
          <div class="infoItem">
          	<span class="infoItemLeft">站长</span>
          	<span class="infoItemRight">{{siteUsername}}</span>
          </div>
          <div class="infoItem">
          	<div class="overHide">
          		<span class="infoItemLeft">站点成员</span>
          		<a href="javascript:;" class="infoItemRight lookMore" @click="moreCilrcleMembers">查看更多<span class="icon iconfont icon-right-arrow"></span></a>
          	</div>
          	<div class="circleMemberList">
              <img v-for="(item,index) in siteInfo.users" :key="index" :src="item._data.avatarUrl" alt="" class="circleMember" v-if="item._data.avatarUrl == '' && item._data.avatarUrl == null" @click="membersJump(item._data.id)">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="circleMember" v-else="" @click="membersJump(item._data.id)">
          	</div>
          </div>
        </div>
        <div class="gap"></div>
        <div class="loginOpera">
        	<p class="welcomeUser">欢迎您，{{username}}<a href="javascript:;" class="signOut" @click="signOut">退出</a></p>
        	<a href="javascript:;" class="regiJoin" @click="sitePayClick(sitePrice)">付费，获得成员权限</a>
        	<p class="payMoney">￥{{sitePrice}} / 永久有效</p>
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
       <img :src="codeUrl" alt="" class="qrCode">
       <p class="payTip">微信识别二维码支付</p>
      </van-popup>
    </div>
    <!-- 付费站点 未登录 -->
    <div class="circleCon" v-else-if="situation3">
      <div v-if="siteInfo">
        <Header :logoShow="true" :perDetShow="true"></Header>
      </div>
      <div class="cirPostCon" v-if="themeCon">
        <div class="postTop">
          <div class="postPer">
            <img :src="themeCon.user._data.avatarUrl" alt="" class="postHead" v-if="themeCon.user._data.avatarUrl == '' && themeCon.user._data.avatarUrl == null">
            <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
            <div class="perDet">
              <div class="perName">{{themeCon.user._data.username}}</div>
              <div class="postTime">{{themeCon.user._data.createdAt}}</div>
            </div>
          </div>
        </div>
        <div class="postContent">
          <a href="javascript:;" v-if="themeCon.firstPost._data.content" v-html="themeCon.firstPost._data.content"></a>
        </div>
      </div>
      <div class="gap"></div>
      <div class="loginOpera">
        <a href="javascript:;" @click="loginJump" class="mustLogin">已注册，登录</a>
        <a href="javascript:;" @click="registerJump" class="regiJoin">立即注册并加入</a>
        <p class="payMoney">￥{{sitePrice}} / 永久有效</p>
      </div>
      <div class="gap"></div>
      <div class="powerListBox">
        <div class="powerTit">作为成员，您将获得以下权限</div>
        <div class="powerList">
          <div class="powerClassify">帖子操作</div>
          <p class="powerChi">查看主题</p>
          <p class="powerChi">发图文帖</p>
          <p class="powerChi">付费阅读帖</p>
          <p class="powerChi">附件查看</p>
          <p class="powerChi">回帖</p>
        </div>
      </div>

    </div>
    <!-- 公开站点 已登录 -->
   <!-- <div class="circleCon" v-else-if="situation4">
      公开站点 已登录
    </div> -->

    <!-- 公开站点 未登录 -->
    <div class="circleCon" v-else="situation5">
      <comHeader title="详情" :menuIconShow="true"></comHeader>
      <div class="content" v-if="themeShow">
         <div class="cirPostCon">
         	<div class="postTop">
         		<div class="postPer">
               <img :src="themeCon.user._data.avatarUrl" alt="" class="postHead" v-if="themeCon.user._data.avatarUrl == '' && themeCon.user._data.avatarUrl == null">
               <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
         			<div class="perDet">
         				<div class="perName">{{themeCon.user._data.username}}</div>
         				<div class="postTime">{{themeCon.user._data.createdAt}}</div>
         			</div>
         		</div>
         		<div class="postOpera">
         			<span class="icon iconfont icon-top"></span>
         		</div>
         	</div>
         	<div class="postContent">
         		<a href="javascript:;" v-if="themeCon.firstPost._data.content" v-html="themeCon.firstPost._data.content">22222</a>
         	</div>
         	<div class="postImgBox">
             <div class="postImgList">
               <van-image
                 fit="none"
                 lazy-load
                 v-for="(image,index)  in themeCon.firstPost.images"
                 src="image._data.fileName"
                 @click="imageSwiper"
               />
             </div>
         	</div>
         	<div class="uploadFileList" v-for="(attachment,index)  in themeCon.firstPost.attachments" :key="index">
         		<a href="javascript:;" class="fileChi">
         			<span class="icon iconfont icon-pdf"></span>
         		  <!-- <span v-if="attachment._data.fileType = rar" class="icon iconfont icon-rar"></span>
         		  <span class="icon iconfont icon-word"></span> -->
         			<span class="fileName">{{attachment._data.fileName}}</span>
         		</a>
         	</div>
         	<div class="postDetBot">
         		<span class="readNum">{{themeCon._data.viewCount}}&nbsp;阅读</span>
         		<!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
             <div class="screen" @click="bindScreen" v-if="">
             	<span>管理</span>
             	<span class="icon iconfont icon-down-menu jtGrayB"></span>
             	<div class="themeList" v-if="showScreen">
             		<a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,2,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canEssence">加精{{themeCon._data.canEssence}}</a>
                 <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,3,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canSticky">置顶</a>
                 <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,4,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="themeCon._data.canDelete">删除</a>
                 <a href="javascript:;"  @click="themeOpera(themeCon.firstPost._data.id,5,themeCon.category._data.id,themeCon.firstPost._data.content)" v-if="">编辑</a>
             	</div>
             </div>
         		<a href="javascript:;" class="postDetR" @click="themeOpera(themeCon.firstPost._data.id,1,themeCon.category._data.id,themeCon.firstPost._data.content)">收藏</a>
         		<a href="javascript:;" class="postDetR" @click="shareTheme">分享</a>
             <input type="button" hidden value="点击复制代码" />
         	</div>
         </div>

         <div class="gap"></div>
         <div class="commentBox padB0">
           <div class="likeBox" v-if="themeCon.firstPost.likedUsers">
             <span class="icon iconfont icon-praise-after"></span>
             <a href="javascript:;" v-for="like in themeCon.firstPost.likedUsers" @click="jumpPerDet(like.id)">{{like._data.username + ','}}</a>&nbsp;等<span>{{themeCon.firstPost._data.likeCount}}</span>个人觉得很赞
           </div>
           <div class="payPer" v-if="themeCon.rewardedUsers">
             <span class="icon iconfont icon-money"></span>
             <img v-for="reward in themeCon.rewardedUsers" v-if="reward.avatarUrl" :src="reward._data.avatarUrl" class="payPerHead">
             <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead">
           </div>
           <div v-for="item in themeCon.posts">
             <div class="commentPostDet">
               <div class="postTop">
                 <div class="postPer">
                   <img v-if="item.user._data.avatarUrl" :src="item.user._data.avatarUrl" class="postHead">
                   <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead">
                   <div class="perDet">
                     <div class="perName">{{item.user._data.username}}</div>
                     <div class="postTime">{{item._data.updatedAt}}</div>
                   </div>
                 </div>
               </div>
               <div class="postContent">
                 <!-- <a href="javascript:;"><blockquote class="quoteCon">dsfhjkdshfkjdhfkjdhk</blockquote>{{item.content()}}</a> -->
                 <a href="javascript:;" v-html="item._data.content"></a>
               </div>
             </div>
             <div class="commentOpera padT22">
               <a v-if="item._data.canDelete" @click="replyOpera(item._data.id,'1')">删除</a>
               <a v-if="item._data.isLiked" @click="replyOpera(item._data.id,'2',item._data.isLiked)"><span class="icon iconfont icon-praise-after" :class="{'icon-like': likedClass}"></span>{{item._data.likeCount}}</a>
               <a v-else="" @click="replyOpera(item._data.id,'2',item._data.isLiked)"><span class="icon iconfont icon-like":class="{'icon-praise-after': likedClass}"></span>{{item._data.likeCount}}</a>
               <a class="icon iconfont icon-review" @click="replyToJump(themeCon._data.id,item._data.id,item._data.content)"></a>
             </div>
           </div>
           <div class="loginOpera">
           	<a href="javascript:;" @click="loginJump" class="mustLogin">立即登录</a>
           	<a href="javascript:;" @click="registerJump" class="regiJoin">注册，并加入</a>
           </div>
         </div>
      </div>
    </div>
</template>

<script>
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import Header from '../../m_site/common//headerView';
import mSiteDetailsCon from '../../../controllers/m_site/circle/detailsCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "detailsView",
    components:{
    	comHeader,
      Header
    },
    ...mSiteHeader,
    ...mSiteDetailsCon
}



</script>
