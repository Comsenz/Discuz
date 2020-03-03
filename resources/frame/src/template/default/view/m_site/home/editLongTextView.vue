<template>
    <div class="post-topic-box">
      <header class="post-topic-header" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-longText-form" id="postForm">
        <input type="text" placeholder="请输入标题" autofocus id="themeTitle" v-model="themeTitle" class="pubThemeTitle">
        <textarea id="textarea_id" class="markdownText" name="post-topic" ref="textarea" placeholder="请输入内容" v-model="content" @focus="showFacePanel = false; footMove = false; payMove=false; markMove=false; keyboard = false;"></textarea>

        <!-- <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea"  placeholder="请输入内容" v-model="content" :maxlength="keywordsMax" @change="searchChange" @focus="showFacePanel = false; footMove = false; keyboard = false;"></textarea> -->
        <div class="uploadBox" v-if="isAndroid && isWeixin">
          <div class="uploadBox" v-if="uploadShow">
            <van-uploader :max-count="12" :after-read="handleFile" v-model="fileListOne" @delete="deleteEnclosure($event,'img')" multiple>
            </van-uploader>
          </div>
        </div>
        <div class="" v-else ="">
          <div class="uploadBox" v-if="uploadShow ">
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
      <markdown-toolbar for="textarea_id" class="markdownBox markdownFix" :class="{'markMove':markMove}" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
        <md-bold><span class="icon iconfont icon-bold"></span></md-bold>
        <md-header><span class="icon iconfont icon-title"></span></md-header>
        <md-italic><span class="icon iconfont icon-italic"></span></md-italic>
        <md-quote><span class="icon iconfont icon-quote"></span></md-quote>
        <md-code><span class="icon iconfont icon-code"></span></md-code>
        <md-link><span class="icon iconfont icon-link"></span></md-link>
        <!-- <md-image>image<span class="icon iconfont icon-italic"></span></md-image> -->
        <md-unordered-list><span class="icon iconfont icon-unordered-list"></span></md-unordered-list>
        <md-ordered-list><span class="icon iconfont icon-ordered-list"></span></md-ordered-list>
        <!-- <md-task-list>task-list<span class="icon iconfont icon-italic"></span></md-task-list> -->
        <!-- <md-mention>mention<span class="icon iconfont icon-italic"></span></md-mention> -->
        <!-- <md-ref>ref<span class="icon iconfont icon-italic"></span></md-ref> -->
      </markdown-toolbar>
      <van-cell title="付费设置" @click="paySetting" is-link :value="payValue" :class="{'payMove':payMove}" class="paySetting" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"/>
      <footer class="post-topic-footer" id="post-topic-footer" :class="{'footMove':footMove}" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
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
            <span  class="icon iconfont icon-enclosure post-topic-header-icon uploadIcon" :class="{'hide': encuploadShow}" v-else="" @click="beforeHandleEnclosure">
            </span>
        </div>
        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>
      <van-popup 
        v-model="paySetShow"
        class="paySetShow"
        click-overlay="closePaySet"
      >
        <div class="popTitBox">
          <span class="popupTit">设置金额</span>
          <span class="icon iconfont icon-closeCho" @click="closePaySet"></span>
        </div>
        <div class="payMoneyBox">
          <span>￥</span>
          <input type="number" class="payMoneyInp" id="payMoneyInp" v-model="paySetValue" autofocus="autofocus" @keyup.enter="search" @input="search($event)" />
        </div>
        <!-- <div class="payEx">付费说明</div>
        <input type="text" placeholder="这篇内容付费方可查看全部内容…" class="payExplain"> -->
        <a href="javascript:;" class="popSureBtn" :class="{ 'sureBtnCli': isCli, 'forbiddenCli': !isCli }" @click="isCli && paySetSure()">确定</a>
      </van-popup>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox" id="showFacePanel" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"></Expression>
      <div class="popup">
        <van-popup v-model="showPopup" position="bottom"  round :style="{ height: '50%' }">
          <van-picker :columns='categories' show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm"  />
        </van-popup>
      </div>
    </div>

</template>

<script>
import editLongTextCon from '../../../controllers/m_site/circle/editLongTextCon';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Expression from '../../m_site/common/expressionView';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';

export default {
    name: "post-topic",
    components: {
      Expression,
    },
  ...editLongTextCon
}
</script>
