<template>
    <div class="post-topic-box">
      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-topic-form">
        <!-- <textarea placeholder="评论" v-model="shareText" ref="shareTextArea" @focus="showFacePanel = false"></textarea> -->
        <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea"  placeholder="请输入内容" v-model="content" :maxlength="keywordsMax" @change="searchChange"@focus="showFacePanel = false;footMove = false;keyboard = false;"></textarea>
        <div class="uploadBox">
          <van-uploader v-model="fileList" multiple />
        </div>
      </div>

      <footer class="post-topic-footer" :class="{'footMove':footMove}">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
           <span  class="icon iconfont icon-picture post-topic-header-icon" ></span>
          <span  class="icon iconfont icon-enclosure post-topic-header-icon" ></span>
        </div>
        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox"></Expression>
      <div class="popup">
        <van-popup v-model="showPopup" position="bottom" round :style="{ height: '50%' }" >
          <van-picker :columns="columns" show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm" />
        </van-popup>
      </div>
    </div>

</template>

<script>
import '../../../scss/m_site/mobileIndex';
import postTopicCon from '../../../controllers/m_site/circle/postTopicCon';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Expression from '../../m_site/common/expressionView';
export default {
    name: "post-topic",
    components: {
      Expression,
    },
  ...postTopicCon
}
</script>
