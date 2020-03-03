<template>
    <div class="post-topic-box">
      <header class="post-topic-header">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-topic-form" id="postForm">
        <!-- <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea"  placeholder="请输入内容" v-model="replyText" :maxlength="keywordsMax" @change="searchChange"@focus="showFacePanel = false"></textarea> -->
         <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea"  placeholder="请输入内容" v-model="replyText" :maxlength="keywordsMax" @change="searchChange"@focus="showFacePanel = false;footMove = false;keyboard = false;"></textarea>

        <!-- <div class="uploadBox" v-show="uploadShow">
          <van-uploader :max-count="12" :after-read="handleFile" accept="image/*" v-model="fileList" @delete="deleteEnclosure($event.id,'img')" multiple></van-uploader>
        </div> -->
        <div class="uploadBox" v-if="isAndroid && isWeixin">
          <div class="uploadBox" v-if="uploadShow">
            <van-uploader :max-count="12" :after-read="handleFile" v-model="fileListOne" @delete="deleteEnclosure($event,'img')" multiple>
            </van-uploader>
          </div>
        </div>
        <div class="" v-else ="">
          <div class="uploadBox" v-if="uploadShow ">
            <!-- <van-uploader :max-count="12" :accept="supportImgExtRes" :after-read="handleFile" v-model="fileListOne" @delete="deleteEnclosure($event,'img')" multiple>
            </van-uploader> -->
            <van-uploader :max-count="12" :accept="supportImgExtRes" multiple="false" :after-read="handleFile" v-model="fileListOne" @delete="deleteEnclosure($event,'img')">
            </van-uploader>
          </div>
        </div>
      </div>

      <footer class="post-topic-footer" id="post-topic-footer" :class="{'footMove':footMove}">
        <div class="post-topic-footer-left reply-topic-footer-left">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
         <!-- <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon">
            <input type="file" accept="image/*" @change="handleFileUp" class="hiddenInput"/>
          </span> -->
          <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon" v-if="canUploadImages && limitMaxLength">
            <input type="file" @change="handleFileUp" class="hiddenInput" v-if="isAndroid && isWeixin"/>
            <input type="file" :accept="supportImgExtRes" @change="handleFileUp" class="hiddenInput" v-else="" multiple/>
          </span>
          <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon" v-else="" @click="beforeHandleFile">
          </span>
        </div>
      </footer>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" id="showFacePanel" class="expressionBox" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"></Expression>
    </div>
</template>

<script>
import Expression from '../../m_site/common/expressionView';
import replyToTopicCon from '../../../controllers/m_site/themeDetails/replyToTopicCon';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
export default {
    name: "reply-to-topic-view",
    components: {
      Expression,
    },
  ...replyToTopicCon
}
</script>
