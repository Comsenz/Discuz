<template>
  <section>
    <div class="cirPostCon" v-for="(item,key) in themeList">
      <div class="">
        <div class="postTop">
          <div class="postPer">
            <img :src="item.postHead" src="../../../../../../static/images/noavatar.gif" class="postHead">
            <div class="perDet">
              <div class="perName">{{item.user().nickname()}}</div>
              <div class="postTime">{{item.user().createdAt()|timeAgo}}</div>
            </div>
          </div>
          <div class="postOpera">
            <!-- <span class="icon iconfont icon-top" v-show="isTopShow"></span> -->
            <span class="icon iconfont icon-top"></span>
            <!-- <div class="moreCli" v-show="isMoreShow"> -->
            <div class="moreCli">
              <span class="icon iconfont icon-more"></span>
            </div>
          </div>
        </div>
        <div class="postContent">
          <a href="javascript:;">{{item.firstPost().content()}}</a>
        </div>
      </div>
      <div class="operaBox">
        <!-- <div class="likeBox">
          <span class="icon iconfont icon-praise-after"></span>
          <i></i><a href="javascript:;" v-for="fabulous in in themeList.themeData.fabulousList">{{fabulous+','}}</a>&nbsp;等<span>{{items.fabulousNum}}</span>个人觉得很赞
        </div> -->
        <!-- <div class="reward">
          <span class="icon iconfont icon-money"></span>
          <a href="javascript:;" v-for="reward in themeList.themeData.rewardList">{{reward+','}}</a>
        </div> -->
        <div class="replyBox">
          <div class="replyCon" v-for="reply in item.lastThreePosts()">
            <a href="javascript:;">{{reply.user().nickname()}}</a>
            <span class="font9">回复</span>
            <a href="javascript:;">{{reply.replyUser().nickname()}}</a>
            <span>{{reply.content()}}</span>
          </div>
          <a href="javascript;" class="allReply">全部{{item.postCount()-1}}条回复<span class="icon iconfont icon-right-arrow"></span></a>
        </div>
      </div>
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
