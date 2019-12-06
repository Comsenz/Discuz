<template>
    <!-- <div class="reply-to-topic-box post-topic-box">
       <header class="post-topic-header">
         <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
         <h2 class="postHeadTit">{{headerTitle}}</h2>
         <van-button type="primary" size="mini" @click="publish">发布</van-button>
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
    </div> -->

    <div class="post-topic-box">
      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-topic-form">
        <!-- <textarea placeholder="评论" v-model="shareText" ref="shareTextArea" @focus="showFacePanel = false"></textarea> -->
        <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea"  placeholder="请输入内容" v-model="replyText" :maxlength="keywordsMax" @change="searchChange"@focus="showFacePanel = false"></textarea>
        <div class="uploadBox">
          <van-uploader v-model="fileList" multiple />
        </div>
      </div>

      <footer class="post-topic-footer" :class="{'footMove':footMove}">
        <div class="post-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
           <span  class="icon iconfont icon-picture post-topic-header-icon" ></span>
          <!-- <span  class="icon iconfont icon-enclosure post-topic-header-icon" ></span> -->
        </div>
      </footer>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox"></Expression>
    </div>
</template>

<script>
// import '../../../../../../static/css/iconfont.css';
import '../../../scss/m_site/mobileIndex';
import Expression from '../../m_site/common/expressionView';
// import '../../../scss/m_site/themeDetails/themeDetails.scss';
import replyToTopicCon from '../../../controllers/m_site/themeDetails/replyToTopicCon';
export default {
    name: "reply-to-topic-view",
    components: {
      Expression,
    },
  ...replyToTopicCon
}
</script>
