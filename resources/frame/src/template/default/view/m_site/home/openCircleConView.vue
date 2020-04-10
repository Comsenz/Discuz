<!--移动端首页模板-->

<template>
  <div class="circleCon">
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
      <comHeader title="详情" :menuIconShow="true"></comHeader>
      <div class="content marBfixed" v-if="themeShow">
        <div class="cirPostCon">
          <div class="postTop">
            <div class="postPer">
              <img
                :src="themeCon.user._data.avatarUrl"
                alt
                class="postHead"
                v-if="themeCon.user._data.avatarUrl == '' && themeCon.user._data.avatarUrl == null"
              />
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postHead" v-else />
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
            <a
              href="javascript:;"
              v-if="themeCon.firstPost._data.content"
              v-html="themeCon.firstPost._data.content"
            >22222</a>
          </div>
          <div class="postImgBox">
            <div class="postImgList">
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postPictures" />
              <img :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="postPictures" />
            </div>
          </div>
          <div class="uploadFileList">
            <a href="javascript:;" class="fileChi">
              <span class="icon iconfont icon-pdf"></span>
              <!-- <span class="icon iconfont icon-rar"></span>
              <span class="icon iconfont icon-word"></span>-->
              <span class="fileName">文档名称.doc</span>
            </a>
          </div>
          <div class="postDetBot">
            <span class="readNum">{{themeCon._data.viewCount}}&nbsp;阅读</span>
            <!-- <a href="javascript:;" class="postDetR">管理<span class="icon iconfont icon-down-menu"></span></a> -->
            <div class="screen" @click="bindScreen">
              <span>管理</span>
              <span class="icon iconfont icon-down-menu jtGrayB"></span>
              <div class="themeList" v-if="showScreen">
                <a
                  href="javascript:;"
                  @click="themeOpera(themeCon.firstPost._data.id,cho.type,themeCon.category._data.id,themeCon.firstPost._data.content)"
                  v-for="(cho,index) in themeChoList"
                  :key="index"
                >{{cho.typeWo}}</a>
              </div>
            </div>
            <a
              href="javascript:;"
              class="postDetR"
              @click="themeOpera(themeCon.firstPost._data.id,1,themeCon.category._data.id,themeCon.firstPost._data.content)"
            >收藏</a>
            <a href="javascript:;" class="postDetR" @click="shareTheme">分享</a>
          </div>
        </div>

        <div class="gap"></div>
        <div class="commentBox">
          <div class="likeBox" v-if="themeCon.firstPost.likedUsers">
            <span class="icon iconfont icon-praise-after"></span>
            <a
              href="javascript:;"
              v-for="like in themeCon.firstPost.likedUsers"
              @click="jumpPerDet(like.id)"
            >{{like._data.username + ','}}</a>&nbsp;等
            <span>{{themeCon.firstPost._data.likeCount}}</span>个人觉得很赞
          </div>
          <div class="payPer" v-if="themeCon.rewardedUsers">
            <span class="icon iconfont icon-money"></span>
            <img
              v-for="reward in themeCon.rewardedUsers"
              v-if="reward.avatarUrl"
              :src="reward._data.avatarUrl"
              class="payPerHead"
            />
            <img v-else :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="payPerHead" />
          </div>
          <div v-for="item in themeCon.posts">
            <div class="commentPostDet">
              <div class="postTop">
                <div class="postPer">
                  <img
                    v-if="item.user && item.user._data.avatarUrl"
                    :src="item.user._data.avatarUrl"
                    class="postHead"
                  />
                  <img
                    v-else
                    :src="appConfig.staticBaseUrl+'/images/noavatar.gif'"
                    class="postHead"
                  />
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
              <a
                v-if="item._data.isLiked"
                @click="replyOpera(item._data.id,'2',item._data.isLiked)"
              >
                <span class="icon iconfont icon-praise-after" :class="{'icon-like': likedClass}"></span>
                {{item._data.likeCount}}
              </a>
              <a v-else @click="replyOpera(item._data.id,'2',item._data.isLiked)">
                <span class="icon iconfont icon-like" :class="{'icon-praise-after': likedClass}"></span>
                {{item._data.likeCount}}
              </a>
              <a
                class="icon iconfont icon-review"
                @click="replyToJump(themeCon._data.id,item._data.id,item._data.content)"
              ></a>
            </div>
          </div>
        </div>
      </div>
    </van-pull-refresh>
  </div>
</template>

<script>
import comHeader from "../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader";
import mSiteDetailsCon from "../../../controllers/m_site/circle/detailsCon";
// import '../../../scss/m_site/mobileIndex.scss';
import "../../../defaultLess/m_site/common/common.less";
import "../../../defaultLess/m_site/modules/circle.less";
export default {
  name: "detailsView",
  components: {
    comHeader
  },
  ...mSiteDetailsCon
};
</script>
