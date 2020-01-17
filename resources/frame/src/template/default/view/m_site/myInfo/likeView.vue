<template>
  <div class="like-box my-info-money-header">
    <LikeHeader title="点赞我的"></LikeHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    finished-text="没有更多了"
    @load="onLoad"
    :immediate-check="false"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    <main class="like-main content" >
      <div class="like-cont cell-crossing" v-for='(item,index) in likeList' :key='index'>
        <ContHeader
          :imgUrl="item._data.user_avatar"
          :userId="item._data.user_id"
          :stateTitle="stateTitle"
          :time="$moment(item._data.created_at).format('YYYY-MM-DD HH:mm')"
          :userName="item._data.user_name">
           <div slot="operating" @click.prevent="deleteReply(item._data.id)">删除</div>
        </ContHeader>
        <div class="likePostContent" v-if="item._data.post_content">
        <a href="javascript:;" v-html="item._data.post_content" ></a>
        </div>
        <!-- <div class="quote-reply" v-if="item._data.post_content">
        <span>{{item._data.post_content}}</span>
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
// import '../../../scss/m_site/mobileIndex.scss';
// import '../../../scss/m_site/mobileIndex.scss';

import '../../../defaultLess/m_site/modules/myInfo.less';
import '../../../defaultLess/m_site/common/common.less';
import likeCon from '../../../controllers/m_site/myInfo/likeCon';
export default {
  name: "like-view",
  ...likeCon
}
</script>

