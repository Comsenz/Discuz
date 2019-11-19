<template>
  <div class="like-box my-info-money-header">
    <LikeHeader title="点赞我的"></LikeHeader>
    <main class="like-main content">
      <div class="like-cont cell-crossing">
        <ContHeader
          :imgUrl="imgUrl"
          :stateTitle="stateTitle"
          :time="time"
          :userName="userName">
        </ContHeader>
        <div class="quote-reply">
          <span>我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</span>
        </div>
      </div>

      <div class="like-cont cell-crossing">
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
      </div>

    </main>
    <footer class="my-info-money-footer"></footer>
  </div>
</template>

<script>
import '../../../less/m_site/myInfo/myInfo.less';
import likeCon from '../../../controllers/m_site/myInfo/likeCon';
import  '../../../scss/m_site/myInfo/myInfo.scss';
import  '../../../scss/m_site/mobileIndex.scss';
export default {
  name: "like-view",
  ...likeCon
}
</script>
