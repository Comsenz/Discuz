<template>
  <div class="like-box my-info-money-header">
    <LikeHeader title="点赞我的"></LikeHeader>
    <main class="like-main">

      <div class="like-cont cell-crossing" v-for='(item,index) in likeList' :key='index'>
        <ContHeader
          :imgUrl="item._data.user_avatar"
          :stateTitle="item._data.thread_title"
          :time="$moment(item._data.created_at).startOf('hour').fromNow()"
          :userName="item._data.user_name">
        </ContHeader>
        <div class="quote-reply">
        <span>{{item._data.post_content}}</span>
        </div>
      </div>

    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import likeCon from '../../../controllers/m_site/myInfo/likeCon';
export default {
  name: "like-view",
  ...likeCon
}
</script>

