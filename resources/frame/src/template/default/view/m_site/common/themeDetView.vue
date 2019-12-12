<template>
  <section>
    <div>

       <van-checkbox-group v-model="result" ref="checkboxGroup">
        <div class="cirPostCon" v-for="(item,key) in themeList">
        <div class="">
          <div class="postTop">
            <div class="postPer">
              <img :src="item.postHead" v-if="item.postHead" class="postHead">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else="">
              <div class="perDet">
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
          <div class="postContent">
            <a @click="jumpThemeDet(item.id())">{{item.firstPost._data.content}}</a>
          </div>
        </div>
        <div class="operaBox">
          <div class="likeBox" v-if="item.firstPost.likedUsers.length>0" v-for="">
            <span class="icon iconfont icon-praise-after"></span>
            <i></i><a v-for="like in item.firstPost.likedUsers" @click="jumpPerDet(like.id)">{{like._data.username + ','}}</a>&nbsp;等<span>{{item._data.likeCount}}</span>个人觉得很赞
          </div>
          <div class="likeBox" v-else="">
          </div>
          <div class="reward" v-if="item.rewardedUsers.length>0">
            <span class="icon iconfont icon-money"></span>
            <a href="javascript:;" v-for="reward in item.rewardedUsers">{{reward._data.username+','}}</a>
          </div>
          <div class="reward" v-else="">
          </div>
          <div class="replyBox">
            <div class="replyCon" v-if="" v-for="reply in item.lastThreePosts">
              <a href="javascript:;">{{reply.user._data.username}}</a>
              <span class="font9">回复</span>
              <a href="javascript:;">{{reply.user._data.username}}</a>
              <span>{{reply.replyUser._data.username}}</span>
            </div>
            <a href="javascript;" class="allReply">全部{{item._data.postCount-1}}条回复<span class="icon iconfont icon-right-arrow"></span></a>
          </div>
        </div>
          <!-- <van-checkbox
              v-if="ischeckShow"
              class="memberCheck"
              :name="item.id"
              ref="checkboxes"
          /> -->
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
