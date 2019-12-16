<template>
  <section>
    <div>
       <van-checkbox-group v-model="result" ref="checkboxGroup">
        <div class="" v-for="(item,key) in themeList">
          <div class="cirPostCon">
            <div class="">
              <div class="postTop">
                <div class="postPer">
                  <img :src="item.postHead" v-if="item.postHead" class="postHead">
                  <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
                  <div class="perDet" v-if="item.user">
                    <div class="perName">{{item.user._data.username}}</div>
                    <div class="postTime">{{item._data.createdAt|timeAgo}}</div>
                  </div>
                </div>
                <div class="postOpera">
                  <span class="icon iconfont icon-top" v-show="isTopShow"></span>
                  <div class="moreCli" v-show="isMoreShow">
                    <span class="icon iconfont icon-more"></span>
                  </div>
                </div>
              </div>
              <div class="postContent" v-if="item.firstPost">
                <a @click="jumpThemeDet(item._data.id)">{{item.firstPost._data.content}}</a>
              </div>
            </div>
            <div class="operaBox">
            <div class="isrelationGap" v-if="item.firstPost.likedUsers.length>0 || item.rewardedUsers.length>0">
            </div>
            <div class="likeBox" v-if="item.firstPost.likedUsers.length>0">
              <span class="icon iconfont icon-praise-after"></span>
              <i></i>
              <a v-for="like in item.firstPost.likedUsers" @click="jumpPerDet(like._data.id)">{{like._data.username + ','}}</a>&nbsp;等<span>{{item._data.likeCount}}</span>个人觉得很赞
            </div>
            
            <div class="reward" v-if="item.rewardedUsers.length>0">
              <span class="icon iconfont icon-money"></span>
              <a href="javascript:;" v-for="reward in item.rewardedUsers">{{reward._data.username+','}}</a>
            </div>
          
            <div class="isrelationLine" v-if="item.firstPost.likedUsers.length>0 || item.rewardedUsers.length>0">
            </div>

              <div class="replyBox" v-if="item.lastThreePosts.length>0">
                <div class="replyCon" v-for="reply in item.lastThreePosts">
                  <a href="javascript:;" v-if="reply.user">{{reply.user._data.username}}</a>
                  <span class="font9" v-if="reply.replyUser">回复</span>
                  <a href="javascript:;" v-if="reply.replyUser">{{reply.replyUser._data.username}}</a>
                  <span>{{reply._data.content}}</span>
                </div>
                <a href="javascript;" class="allReply" v-if="item._data.postCount>4">全部{{item._data.postCount-1}}条回复<span class="icon iconfont icon-right-arrow"></span></a>
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
