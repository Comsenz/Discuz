<template>
  <div class="reward-box my-info-money-header">
    <RewardHeader title="打赏我的"></RewardHeader>
    <van-list
    v-model="loading"
    :finished="finished"
    finished-text="没有更多了"
    :offset="offset"
    @load="onLoad"
    >
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
    <main class="reward-main content">
      <div class="reward-con cell-crossing" v-for='(item,index) in rewardList' :key='index'>
        <ContHeader
          :imgUrl="item._data.user_avatar"
          :stateTitle="stateTitle"
          :userId="item._data.user_id"
          :amount="item._data.amount"
          :time="$moment(item._data.created_at).format('YYYY-MM-DD HH:mm')"
          :userName="item._data.user_name">
           <div slot="operating" @click.prevent="deleteReply(item._data.id)">删除</div>
        </ContHeader>
         <div class="likePostContent">
          <a href="javascript:;" v-html="item._data.content"></a>
          <!-- <a href="javascript:;">
            <blockquote class="quoteCon">asdasdsadasdasdasdasdaasdadasd</blockquote>
            我们的观点不一样
          </a> -->
        </div>
          <!-- <div class="reference">
          <div class="reference-cont">
            <span>{{item._data.content}}</span>
          </div>
        </div> -->
        <!-- <ContMain
          :contText="contText">
        </ContMain> -->
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
import rewardCon from '../../../../controllers/m_site/myInfo/myNotice/rewardCon'
export default {
  name: "reward",
  ...rewardCon
}
</script>
