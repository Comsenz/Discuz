<template>
  <div class="reply-my-box my-info-money-header">
    <ReplyHeader title="回复我的"></ReplyHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    finished-text="没有更多了"
    :offset = "offset"
    @load="onLoad"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    <main class="reply-my-main content">
      <div class="reply-my-cont cell-crossing" v-for='(item,index) in replyList' :key='index'>
        <ContHeader
          :imgUrl="item._data.user_avatar"
          :stateTitle="stateTitle"
          :userId="item._data.user_id"
          :time="$moment(item._data.created_at).format('YYYY-MM-DD HH:mm')"
          :userName="item._data.user_name">
          <div slot="operating" @click.prevent="deleteReply(item._data.id)">删除</div>
        </ContHeader>
        <div class="likePostContent">
          <a href="javascript:;" v-html="item._data.post_content"></a>
          <!-- <a href="javascript:;">
            <blockquote class="quoteCon">asdasdsadasdasdasdasdaasdadasd</blockquote>
            我们的观点不一样
          </a> -->
        </div>
        <!-- <div class="reference">
          <div class="reference-cont">
            <span>{{item._data.post_content}}</span>
          </div>
        </div>
        <div class="quote-reply">
          <span>我们的观点不一样</span>
        </div> -->
      </div>
    </main>
    </van-pull-refresh>
    </van-list>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
// import '../../../less/m_site/myInfo/myInfo.less';
// import  '../../../scss/m_site/mobileIndex.scss';

import '../../../../defaultLess/m_site/common/common.less';
import '../../../../defaultLess/m_site/modules/myInfo.less';
import replyCon from '../../../../controllers/m_site/myInfo/myNotice/replyCon';
export default {
  name: "reply",
  ...replyCon
}
</script>
