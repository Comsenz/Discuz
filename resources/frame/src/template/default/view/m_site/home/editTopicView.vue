<template>
    <div class="post-topic-box">
      <header class="post-topic-header" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-topic-form" id="postForm">
        <!-- <textarea placeholder="评论" v-model="shareText" ref="shareTextArea" @focus="showFacePanel = false"></textarea> -->
        <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea" autofocus placeholder="请输入内容" v-model="content" :maxlength="keywordsMax"   @focus="showFacePanel = false;footMove = false;keyboard = false;"></textarea>
        <!-- <div class="uploadBox" v-if="uploadShow">
          <van-uploader :max-count="12" :after-read="handleFile" accept="image/*" v-model="fileListOne" @delete="deleteEnclosure($event.id,'img')" multiple>
          </van-uploader>
        </div> -->
        <!-- <div class="uploadBox" v-if="uploadShow">
          <van-uploader :max-count="12" :after-read="handleFile" :accept="supportImgExt"  v-model="fileListOne" @delete="deleteEnclosure($event,'img')" multiple>
          </van-uploader>
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
        <div class="enclosure" v-if="enclosureShow">
          <div class="enclosureChi" v-for="(enc,index) in enclosureList" :key="index">
            <span v-if="enc.type === 'rar'" class="icon iconfont icon-rar"></span>
            <span v-if="enc.type === 'zip'" class="icon iconfont icon-rar"></span>
            <span v-else-if="enc.type === 'docx'" class="icon iconfont icon-word"></span>
            <span v-else-if="enc.type === 'doc'" class="icon iconfont icon-word"></span>
            <span v-else-if="enc.type === 'pdf'" class="icon iconfont icon-pdf"></span>
            <span v-else-if="enc.type === 'jpg'" class="icon iconfont icon-jpg"></span>
            <span v-else-if="enc.type === 'mp'" class="icon iconfont icon-mp3"></span>
            <span v-else-if="enc.type === 'mp1'" class="icon iconfont icon-mp4"></span>
            <span v-else-if="enc.type === 'png'" class="icon iconfont icon-PNG"></span>
            <span v-else-if="enc.type === 'ppt'" class="icon iconfont icon-ppt"></span>
            <span v-else-if="enc.type === 'swf'" class="icon iconfont icon-swf"></span>
            <span v-else-if="enc.type === 'TIFF'" class="icon iconfont icon-TIFF"></span>
            <span v-else-if="enc.type === 'txt'" class="icon iconfont icon-txt"></span>
            <span v-else-if="enc.type === 'xls'" class="icon iconfont icon-xls"></span>
            <span v-else="" class="icon iconfont icon-doubt"></span>
            <span class="encName">{{enc.name}}</span>
            <van-icon @click="deleteEnc(enc,'enclosure')" name="clear" class="encDelete"/>
          </div>
        </div>
      </div>

      <footer class="post-topic-footer" id="post-topic-footer" :class="{'footMove':footMove}">
        <div class="post-topic-footer-left" :class="{'width20': encuploadShow}">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
          <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon" v-if="canUploadImages && limitMaxLength">
            <input type="file" @change="handleFileUp" class="hiddenInput" v-if="isAndroid && isWeixin"/>
            <input type="file" :accept="supportImgExtRes" @change="handleFileUp" class="hiddenInput" v-else="" multiple/>
          </span>
          <span  class="icon iconfont icon-picture post-topic-header-icon uploadIcon" v-else="" @click="beforeHandleFile">
          </span>
          <span class="icon iconfont icon-enclosure post-topic-header-icon uploadIcon" :class="{'hide': encuploadShow}" v-if="canUploadAttachments && limitMaxEncLength">
            <input type="file" @change="handleEnclosure" class="hiddenInput"/>
          </span>
          <span  class="icon iconfont icon-enclosure post-topic-header-icon uploadIcon":class="{'hide': encuploadShow}" v-else="" @click="beforeHandleEnclosure">
          </span>
        </div>

        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"></Expression>
      <div class="popup">
        <van-popup v-model="showPopup" position="bottom"  round :style="{ height: '50%' }">
          <van-picker :columns='categories' show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm"  />
        </van-popup>
      </div>
    </div>

</template>

<script>
// import '../../../scss/m_site/mobileIndex';
import editTopicCon from '../../../controllers/m_site/circle/editTopicCon';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Expression from '../../m_site/common/expressionView';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
export default {
    name: "post-topic",
    components: {
      Expression,
    },
  ...editTopicCon
}
</script>
