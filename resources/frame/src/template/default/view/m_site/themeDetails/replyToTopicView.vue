<template>
    <div class="reply-to-topic-box">
      <header class="reply-to-topic-header">
        <span class="icon iconfont icon-back reply-to-topic-header-icon" ></span>
        <h2>{{headerTitle}}</h2>
        <van-button type="primary" class="reply-to-topic-release-btn" size="mini">发布</van-button>
      </header>

      <main class="reply-to-topic-main">
        <textarea class="reply-box" name="reply-to-topic-text" id="reply-to-topic-main-text" placeholder="请输入内容" ></textarea>
      </main>

      <footer class="reply-to-topic-footer">
        <div class="reply-to-topic-footer-left">
          <span  class="icon iconfont icon-label reply-to-topic-header-icon" ></span>
          <span  class="icon iconfont icon-picture reply-to-topic-header-icon" ></span>
        </div>
      </footer>
    </div>
</template>

<script>
import '../../../../../../static/css/iconfont.css';
import '../../../scss/m_site/themeDetails/themeDetails.scss';
import replyToTopicCon from '../../../controllers/m_site/themeDetails/replyToTopicCon';
export default {
    name: "reply-to-topic-view",
  ...replyToTopicCon
}
</script>
