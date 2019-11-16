<template>
  <section>
    <div class="cirPostCon" v-for="(items,key) in themeList.data" v-bind="themeList">
      <div class="">
        <div class="postTop">
          <div class="postPer">
            <img :src="items.postHead" src="../../../../../../static/images/noavatar.gif" class="postHead">

            <div class="perDet">
              <!-- <div class="perName">{{items.postName}}</div>
              <div class="postTime">{{items.createdAt}}</div> -->

              <div class="perName">名字</div>
              <div class="postTime">{{items.attributes.createdAt}}</div>
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
          <a href="javascript:;">{{themeList.included[key].attributes.content}}</a>
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
          <!-- <div class="replyCon" v-for="reply in items.relationships"> -->
          <div class="replyCon">
            <a href="javascript:;">{{themeList.included[key].content}}</a>
            <span class="font9" v-show="replyTag">回复</span>
            <a href="javascript:;" v-show="replyTag">{{themeList.included[key].attributes.content}}</a>
            <span>{{themeList.included[key].content}}</span>
          </div>
          <!-- <a href="javascript;" class="allReply">全部27条回复<span class="icon iconfont icon-right-arrow"></span></a> -->
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
