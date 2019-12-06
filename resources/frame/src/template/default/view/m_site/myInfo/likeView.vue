<template>
  <div class="like-box my-info-money-header">
    <comHeader title="点赞我的"></comHeader>
     <div class="content">
        <Header :userInfoList="likeList" :navShow="false" :invitePerDet="true" :headFixed="false" :logoShow="false" :searchIconShow="false" :menuIconShow="false" :invitationShow="false" :perDetShow="false"></Header>
        <div class="gap"></div>
        <ThemeDet :themeList="likeList"></ThemeDet>
    </div>
    <!-- <main class="like-main content">
      <div class="like-cont cell-crossing" v-for="(item,index) in likeList" :key="index">
        <ContHeader
          :imgUrl="imgUrl"
          stateTitle="点赞我的"
          :time="$moment(item.read_at()).startOf('hour').fromNow()"
          :userName="item.detail().user_name">
        </ContHeader>
        <div class="quote-reply">
          <span>{{item.detail().post_content}}</span>
        </div>
      </div> -->

      <!-- <div class="like-cont cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <div class="reference">
          <div class="reference-cont">
            <span>我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</span>
          </div>
        </div>
        <div class="quote-reply">
          <span>我们的观点不一样</span>
        </div>
      </div> -->

    <!-- </main> -->
    <footer class="home-page-footer">
      <p>上划加载更多</p>
    </footer>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import likeCon from '../../../controllers/m_site/myInfo/likeCon';
import ThemeDet from '../../m_site/common/themeDetView';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import  '../../../scss/m_site/myInfo/myInfo.scss';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "like-view",
   components:{
      comHeader,
    	// Header,
      ThemeDet,
    },
  ...likeCon
}
</script>
