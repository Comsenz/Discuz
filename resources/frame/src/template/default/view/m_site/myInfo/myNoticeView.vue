<template>
    <div class="my-notice-box my-info-money-header">
      <MyNoticeHeader title="我的通知"></MyNoticeHeader>
      <main class="my-notice-main content">
        <van-cell cless="my-notice-cell" @click="myJump('reply')" is-link>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >回复我的</span>
          </template>
        </van-cell>

        <van-cell cless="my-notice-cell" @click="myJump('reward')" is-link>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >打赏我的</span>
          </template>
        </van-cell>

        <van-cell cless="my-notice-cell" @click="myJump('like')" is-link>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >点赞我的</span>
            <i class="custom-title-icon" type="danger" >9</i>
          </template>
        </van-cell>

      </main>
      <footer class="my-info-money-footer"></footer>
    </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import myNoticeCon from '../../../controllers/m_site/myInfo/myNoticeCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
    name: "my-notice",
  ...myNoticeCon
}
</script>
