<template>
    <div class="post-topic-box">
      <header class="post-topic-header" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
        <span @click="backClick"  class="icon iconfont icon-back post-topic-header-icon" ></span>
        <h2 class="postHeadTit">{{headerTitle}}</h2>
        <van-button type="primary" size="mini" @click="publish">发布</van-button>
      </header>

      <div class="post-topic-form" id="postForm">
        <textarea class="reply-box" id="post-topic-form-text" name="post-topic" ref="textarea" autofocus placeholder="请输入内容" v-model="content" :maxlength="keywordsMax"   @focus="showFacePanel = false;footMove = false;keyboard = false;"></textarea>
        <form ref="vExample">
          <input type="file" style="display:none;" :accept="supportVideoExtRes" ref="vExampleFile" @change="vExampleUpload" />
        </form>
        <div class="vedioUpBox" v-if="videoUp">
          <div class="videoUploader" @click="vExampleAdd">
            <span class="icon iconfont icon-add"></span>
          </div>
        </div>
        <!-- 上传完成后展示上传文件名 -->
        <div class="videoCon" v-if="videoShow">
          <div class="videoChi">
            <span class="icon iconfont icon-video1"></span>
            <span class="videoName">{{vcVideoName}}</span>
            <van-icon @click="videoDeleClick()" name="clear" class="videoDelete"/>
          </div>
        </div>
      </div>
      <van-cell title="付费设置" @click="paySetting" is-link :value="payValue" :class="{'payMove':payMove}" class="paySetting borderT" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"/>
      <footer class="post-topic-footer" id="post-topic-footer" :class="{'footMove':footMove}" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
        <div class="post-topic-footer-left" :class="{'width20': encuploadShow}">
          <span  class="icon iconfont icon-label post-topic-header-icon" :class="{'icon-keyboard':keyboard}" @click="addExpression"></span>
        </div>

        <div @click="dClick"  class="post-topic-footer-right">
          <span class="post-topic-footer-right-sort">{{selectSort}}</span>
          <span class="icon iconfont icon-down-menu post-topic-header-icon" style="color: #888888;"></span>
        </div>
      </footer>
      <!-- 设置金额 -->
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
          <input type="number" class="payMoneyInp" autofocus id="payMoneyInp" v-model="paySetValue" @keyup.enter="search" @input="search($event)" />
        </div>
        <!-- <div class="payEx">付费说明</div>
        <input type="text" placeholder="这篇内容付费方可查看全部内容…" class="payExplain"> -->
        <a href="javascript:;" class="popSureBtn" :class="{ 'sureBtnCli': isCli, 'forbiddenCli': !isCli }" @click="isCli && paySetSure()">确定</a>
      </van-popup>
      <Expression :faceData="faceData" @onFaceChoose="handleFaceChoose" v-if="showFacePanel" class="expressionBox" :style="{'overflow': 'hidden','width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}"></Expression>
      <div class="popup">
        <van-popup v-model="showPopup" position="bottom"  round :style="{ height: '50%' }">
          <van-picker :columns='categories' show-toolbar title="选择分类"  @cancel="onCancel" @confirm="onConfirm"  />
        </van-popup>
      </div>
      <div class="loadFix" v-if="loading">
        <div class="loadMask"></div>
        <van-loading color="#333333"  class="loadIcon" type="spinner"/>
      </div>
    </div>

</template>

<script>
import editVideoCon from '../../../../controllers/m_site/circle/edit/editVideoCon';
import Expression from '../../../m_site/common/expressionView';
import  '../../../../defaultLess/m_site/common/common.less';
import  '../../../../defaultLess/m_site/modules/circle.less';
export default {
    name: "post-video",
    components: {
      Expression,
    },
  ...editVideoCon
}
</script>
