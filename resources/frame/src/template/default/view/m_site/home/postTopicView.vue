<template>
    <div class="post-topic-box">
      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2>{{headerTitle}}</h2>
        <a href="javascript:;" class=" weui-btn weui-btn_mini weui-btn_primary post-topic-release-btn">发布</a>
      </header>
      <div class="post-topic-form">
        <textarea id="post-topic-form-text" name="post-topic"   placeholder="请输入内容"></textarea>
      </div>
      <footer class="post-topic-footer">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" ></span>
          <span  class="icon iconfont icon-picture post-topic-header-icon" ></span>
          <span  class="icon iconfont icon-enclosure post-topic-header-icon" ></span>
        </div>
        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" ></span>
        </div>
      </footer>
    </div>
</template>

<script>
import '../../../../../../static/css/iconfont.css';
import '../../../scss/mobile/postTopicView.scss'
import postTopicCon from '../../../controllers/m_site/home/postTopic/postTopicCon'
export default {
    name: "post-topic",
  ...postTopicCon,
}
</script>
