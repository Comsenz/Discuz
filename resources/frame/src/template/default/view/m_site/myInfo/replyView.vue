<template>
  <div class="reply-my-box my-info-money-header">
    <ReplyHeader title="回复我的"></ReplyHeader>
    <main class="reply-my-main content">
      <div class="reply-my-cont cell-crossing" v-for="(item,index) in replyList" :key="index">
        <!-- <ContHeader
          :imgUrl="item"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
          <div slot="operating">删除</div>
        </ContHeader> -->
        <div class="reference">
          <div class="reference-cont">
            <span>我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</span>
          </div>
        </div>
        <div class="quote-reply">
          <span>我们的观点不一样</span>
        </div>
      </div>
      <!-- <div class="reply-my-cont cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
          <div slot="operating">删除</div>
        </ContHeader>
        <div class="quote-reply">
          <span>我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</span>
        </div>
      </div>
      <div class="reply-my-cont cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
          <div slot="operating">删除</div>
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
    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import replyCon from '../../../controllers/m_site/myInfo/replyCon';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "reply",
  ...replyCon
}
</script>
