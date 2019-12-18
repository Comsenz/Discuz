<template>
    <div class="my-notice-box my-info-money-header">
      <MyNoticeHeader title="我的通知"></MyNoticeHeader>
      <main class="my-notice-main content">
        <van-cell cless="my-notice-cell" @click="myJump(item.routerName)" is-link v-for='(item,index) in num' :key='index'>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >{{item.title}}</span>
            <i class="custom-title-icon" type="danger" v-show ='item.number === 0 ? false:true'>{{item.number}}</i>
          </template>
        </van-cell>

        <!-- <van-cell cless="my-notice-cell" @click="myJump('reward')" is-link>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >打赏我的</span>
            <i class="custom-title-icon" type="danger" v-show ='this.numReward == 0 ? false:true'>{{numReward}}</i>
          </template>
        </van-cell>

        <van-cell cless="my-notice-cell" @click="myJump('like')" is-link>
          <template class="my-notice-cell-template" slot="title">
            <span class="custom-title" >点赞我的</span>
            <i class="custom-title-icon" type="danger" v-show ='this.num == 0 ? false:true'>{{num}}</i>
          </template>
        </van-cell> -->

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
