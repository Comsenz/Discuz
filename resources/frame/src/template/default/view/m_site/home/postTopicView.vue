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
        <div class="uploadBox" v-show="uploadShow">
          <van-uploader :max-count="12" :after-read="handleFile" :before-delete="deleteFile" accept="image/*"  v-model="fileList">
          </van-uploader>
          <div class="enclosure">
            <div class="enclosureChi" v-model="enclosureList" v-for="(enc,index) in enclosureList" :key="index">
              <span v-if="enc.type='doc'" class="icon iconfont icon-word"></span>
              <span v-if="enc.type='pdf'" class="icon iconfont icon-pdf"></span>
              <span v-if="enc.type='rar'" class="icon iconfont icon-rar"></span>
              <span class="encName">{{enc.name}}</span>
              <!-- <i class="encDelete"><span class="icon iconfont icon-close"></span></i> -->
              <i class="encDelete">X</i>
            </div>
          </div>
        </div>
      </div>

      <footer class="post-topic-footer" :class="{'footMove':footMove}">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
          <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon">
            <input type="file" accept="image/*" @change="handleFileUp" class="hiddenInput"/>
          </span>
          <span  class="icon iconfont icon-enclosure post-topic-header-icon uploadIcon">
            <input type="file" accept="image/*" @change="handleEnclosure" class="hiddenInput"/>
          </span>
        </div>
        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox"></Expression>
      <div class="popup">
        <van-popup v-model="showPopup" position="bottom"  round :style="{ height: '50%' }">
          <van-picker :columns='categories' show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm"  />
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
