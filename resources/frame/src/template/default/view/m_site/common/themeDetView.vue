<template>
  <section>
    <div>
       <van-checkbox-group v-model="result" ref="checkboxGroup">
        <div class="" @click="disappear()" v-for="(item,index) in themeList" :key="index">
          <div class="cirPostCon">
            <div class="">
              <div class="postTop">
                <div class="postPer">
                  <img :src="item.user._data.avatarUrl" v-if="item.user._data.avatarUrl" class="postHead">
                  <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
                  <div class="perDet">
                    <div class="perName" v-if="item.user">{{item.user._data.username}}</div>
                    <div class="perName" v-else="">该用户已被删除</div>
                    <div class="postTime">{{$moment(item._data.createdAt).format('YYYY-MM-DD HH:mm')}}</div>
                  </div>
                </div>
                <div class="postOpera">
                  <span class="icon iconfont icon-top" v-if="item._data.isSticky" v-show="isTopShow"></span>
                  <div class="screen" ref="screenDiv" @click="bindScreen(index)" v-if="isMoreShow && (item._data.canEssence || item._data.canSticky || item._data.canDelete || item._data.canEdit)">
                  	<div class="moreCli"><span class="icon iconfont icon-more"></span></div>
                  	<div class="themeList" v-show="indexlist==index" >
                      <a href="javascript:;"  @click="replyOpera(item.firstPost._data.id,2,item.firstPost._data.isLiked,false)" v-if="item.firstPost._data.canLike && item.firstPost._data.isLiked">取消点赞</a>
                      <a href="javascript:;"  @click="replyOpera(item.firstPost._data.id,2,item.firstPost._data.isLiked,true)" v-if="item.firstPost._data.canLike && !item.firstPost._data.isLiked">点赞</a>
                      <!-- <a href="javascript:;"  @click="replyToJump(item._data.id,false,false)">回复</a> -->

                  		<a href="javascript:;"  @click="themeOpera(item._data.id,2,false)" v-if="item._data.canEssence && item._data.isEssence">取消加精</a>
                      <a href="javascript:;"  @click="themeOpera(item._data.id,2,true)" v-if="item._data.canEssence && !item._data.isEssence">加精</a>

                      <a href="javascript:;"  @click="themeOpera(item._data.id,3,false)" v-if="item._data.canSticky && item._data.isSticky">取消置顶</a>
                      <a href="javascript:;"  @click="themeOpera(item._data.id,3,true)" v-if="item._data.canSticky && !item._data.isSticky">置顶</a>

                      <a href="javascript:;"  @click="themeOpera(item._data.id,4)" v-if="item._data.canDelete">删除</a>
                  	</div>
                  </div>
                </div>
              </div>
              <div class="postContent" v-if="item.firstPost">
                <a @click="jumpThemeDet(item._data.id,item._data.canViewPosts)" v-html="item.firstPost._data.contentHtml"></a>
              </div>

              <!-- <div class="themeImgBox" v-if="item.firstPost.imageList && item.firstPost.imageList.length>0"> -->
              <div class="themeImgBox" v-if="item.firstPost.imageList && item.firstPost.imageList.length>0">
                <!-- <div class="themeImgList">
                  <van-image
                    fit="cover"
                    width="113px"
                    height="113px"
                    lazy-load
                    v-for="(image,index)  in item.firstPost.imageList"
                    :src="image"
                    @click="imageSwiper"
                    class=""
                  />
                </div> -->
                <!-- <img src="aaaa.png" v-bind:is="auth-img" alt=""/> -->

                <div class="themeImgList moreImg">
                  <van-image
                    fit="cover"
                    lazy-load
                    v-for="(image,index)  in item.firstPost.imageList"
                    :src="image"
                    class="themeImgChild"
                    :key="index"
                    @click="jumpThemeDet(item._data.id,item._data.canViewPosts)"
                  />
                </div>
              </div>
            </div>
            <div class="operaBox">
            <div class="isrelationGap" v-if="item.firstPost.likedUsers.length>0 || item.rewardedUsers.length>0">
            </div>
            <div class="likeBox" v-if="item.firstPost.likedUsers.length>0">
              <span class="icon iconfont icon-praise-after"></span>
              <a  @click="jumpPerDet(like._data.id)" v-for="like in item.firstPost.likedUsers">{{userArr(item.firstPost.likedUsers)}}</a>
              <i v-if="item.firstPost._data.likeCount>10">&nbsp;等<span>{{item.firstPost._data.likeCount}}</span>个人觉得很赞</i>
            </div>

            <div class="reward" v-if="item.rewardedUsers.length>0">
              <span class="icon iconfont icon-money"></span>
              <a href="javascript:;" v-for="reward in item.rewardedUsers" @click="jumpPerDet(reward._data.id)">{{userArr(item.rewardedUsers)}}</a>
            </div>

            <div class="isrelationLine" v-if="(item.lastThreePosts.length>0 && item.firstPost.likedUsers.length>0) || (item.lastThreePosts.length>0 && item.rewardedUsers.length>0)">
            </div>

              <div class="replyBox" v-if="item.lastThreePosts.length>0">
                <div class="replyCon" v-for="reply in item.lastThreePosts">
                  <a href="javascript:;" v-if="reply.user">{{reply.user._data.username}}</a>
                  <a href="javascript:;" v-else="">该用户已被删除</a>
                  <span class="font9" v-if="reply._data.replyUserId">回复</span>
                  <!-- <span class="font9" v-else=""></span> -->
                  <a href="javascript:;" v-if="reply._data.replyUserId && reply.replyUser">{{reply.replyUser._data.username}}</a>
                  <a href="javascript:;" v-else-if="reply._data.replyUserId && !reply.replyUser">该用户已被删除</a>
                  <span v-html="reply._data.contentHtml"></span>
                </div>
                <a @click="jumpThemeDet(item._data.id,item._data.canViewPosts)" class="allReply" v-if="item._data.postCount>4">全部{{item._data.postCount-1}}条回复<span class="icon iconfont icon-right-arrow"></span></a>
              </div>
            </div>
            <van-checkbox
                v-if="ischeckShow"
                class="memberCheck"
                :name="item._data.id"
                ref="checkboxes"
            />
          </div>
          <div class="gap"></div>
        </div>
         <div class="manageFootFixed choFixed" v-if="ischeckShow">
          <a href="javascript:;" @click="checkAll">全选</a>
			    <a href="javascript:;" @click="signOutDele">取消全选</a>
			    <button class="checkSubmit" @click="deleteAllClick" >删除选中</button>
		    </div>
     </van-checkbox-group>
    </div>

    <van-image-preview
      v-model="imageShow"
      :images="priview"
      @change="onChange"
    >
      <template v-slot:index>第{{ index }}页</template>
    </van-image-preview>
  </section>
</template>
<script>
import themeDet from '../../../controllers/m_site/common/themeDetCon';
import '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "themeDetView",
  ...themeDet
}

</script>
