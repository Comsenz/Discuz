<template>
    <div class="post-topic-box">
      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont post-topic-header-icon" >&#xe605;</span>
        <h2>{{headerTitle}}</h2>
        <span  class="icon iconfont post-topic-header-icon" >&#xe60c;</span>
      </header>
      <div class="post-topic-form">
        <textarea id="post-topic-form-text" name="post-topic"   placeholder="请输入内容"></textarea>
      </div>
      <footer class="post-topic-footer">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont post-topic-header-icon" >&#xe608;</span>
          <span  class="icon iconfont post-topic-header-icon" >&#xe612;</span>
          <span  class="icon iconfont post-topic-header-icon" >&#xe60a;</span>
        </div>
        <div class="post-topic-footer-right">
          <span>{{selectSort}}</span>
          <span @click="dClick"  class="icon iconfont post-topic-header-icon" >&#xe60a;</span>
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
