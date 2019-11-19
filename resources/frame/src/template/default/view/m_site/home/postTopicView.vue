<template>
    <div class="post-topic-box">

      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini">发布</van-button>
      </header>

      <div class="post-topic-form">
        <textarea class="reply-box" id="post-topic-form-text" name="post-topic"   placeholder="请输入内容"></textarea>
      </div>

      <footer class="post-topic-footer">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" ></span>
          <span  class="icon iconfont icon-picture post-topic-header-icon" ></span>
          <span  class="icon iconfont icon-enclosure post-topic-header-icon" ></span>
        </div>
        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>

      <div class="popup">
        <van-popup v-model="showPopup" position="bottom" round :style="{ height: '50%' }" >
          <van-picker :columns="columns" show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm" />
        </van-popup>
      </div>
    </div>

</template>

<script>
import '../../../../../../static/css/iconfont.css';
import '../../../scss/m_site/postTopicView.scss'
import postTopicCon from '../../../controllers/m_site/home/postTopic/postTopicCon'
export default {
    name: "post-topic",
  ...postTopicCon,
}
</script>
