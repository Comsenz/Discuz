<template>
    <div class="searchBox">
      <form action="/">
        <van-search
          v-model="searchVal"
          placeholder="搜索用户和主题"
          background="#f8f8f8"
          show-action
          @search="onSearch"
          @cancel="onCancel"
          class="searchCon"
        />
      </form>
      <div class="searchRes">
        <h2 class="resultTit">用户</h2>
        <div class="resUser">
          <img src="../../../../../../static/images/noavatar.gif" class="resUserHead">
          <div class="resUserDet">
            <span class="resUserName">多少分接<i>你</i>口的是否健康的首付款觉得第三方第三方是的是的是的所舒服的</span>
            <span class="userRole">圈主</span>
          </div>
        </div>
        <div class="resUser">
          <img src="../../../../../../static/images/noavatar.gif" class="resUserHead">
          <div class="resUserDet">
            <span class="resUserName">多少分接<i>你</i>口的是否</span>
            <span class="userRole">合伙人</span>
          </div>
        </div>
        <div class="searchMore">
          <i class="icon iconfont icon-search"></i>
          搜索更多用户
        </div>
      </div>
      <div class="gap"></div>
      <div class="searchRes">
        <div class="themeRes">
          <div class="postTop">
            <div class="postPer">
              <img src="../../../../../../static/images/noavatar.gif" class="postHead">
              <div class="perDet">
                <div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
                <div class="postTime">1小时前</div>
              </div>
            </div>
          </div>
          <div class="postContent">
            <a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
          </div>
        </div>
        <div class="themeRes">
          <div class="postTop">
            <div class="postPer">
              <img src="../../../../../../static/images/noavatar.gif" class="postHead">
              <div class="perDet">
                <div class="perName">ElizabethElizabethElizabethElizabethElizabethElizabeth</div>
                <div class="postTime">1小时前</div>
              </div>
            </div>
          </div>
          <div class="postContent">
            <a href="javascript:;">我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。</a>
          </div>
        </div>
        <div class="searchMore">
          <i class="icon iconfont icon-search"></i>
          搜索更多用户
        </div>
      </div>
    </div>
</template>
<style type="text/css" scoped>
  .searchBox { height: 100%; background: #EEEFEF; }
  .searchCon { box-shadow: 0px 1px rgba(0,0,0,0.02); margin-bottom: 1px;}
</style>
<script>
import  '../../../scss/m_site/mobileIndex.scss';
import searchCon from '../../../controllers/m_site/search/searchCon'
export default {
    name: "searchView",
  ...searchCon
}
</script>
